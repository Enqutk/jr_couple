<?php

namespace App\Filament\Actions;

use App\Filament\Resources\BlogPostResource;
use App\Services\TikTokBlogImporter;
use App\Services\TikTokOEmbedService;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use RuntimeException;

class ImportTikTokPostAction
{
    public static function make(): Action
    {
        return Action::make('importTikTok')
            ->label('Import from TikTok')
            ->icon('heroicon-o-play')
            ->color('gray')
            ->modalHeading('Import a TikTok')
            ->modalDescription('Give the account, paste one video, then choose whether to put it on the blog — and whether to keep the original caption.')
            ->modalSubmitActionLabel('Save')
            ->modalWidth('lg')
            ->fillForm(function (): array {
                return [
                    'account' => app(TikTokBlogImporter::class)->rememberedHandle(),
                    'post_to_website' => true,
                    'use_exact_caption' => true,
                ];
            })
            ->form([
                Forms\Components\Select::make('account')
                    ->label('TikTok account')
                    ->options(fn (): array => app(TikTokBlogImporter::class)->accountOptions())
                    ->helperText('JR Couple is the main account. Ruth’s Hair and JR Mobile have their own pages. Paste a video from the account you pick.')
                    ->searchable(),
                Forms\Components\TextInput::make('video_url')
                    ->label('TikTok video URL')
                    ->url()
                    ->required()
                    ->live(onBlur: true)
                    ->helperText('Open the video on TikTok → Share → Copy link.')
                    ->afterStateUpdated(function (?string $state, Set $set, Get $get): void {
                        $set('tiktok_error', null);
                        $set('tiktok_caption', null);
                        $set('tiktok_author', null);
                        $set('tiktok_handle', null);

                        $meta = app(TikTokOEmbedService::class)->fetch($state);

                        if ($meta === null) {
                            if (filled($state)) {
                                $set('tiktok_error', 'Could not read that TikTok. Make sure the video is public and the link is a video, not just the profile.');
                            }

                            return;
                        }

                        $set('tiktok_caption', $meta['caption']);
                        $set('tiktok_author', $meta['author_name']);
                        $set('tiktok_handle', $meta['handle']);

                        if (blank($get('account')) && filled($meta['handle'])) {
                            $set('account', $meta['handle']);
                        }

                        if ($get('use_exact_caption')) {
                            $set('caption', $meta['caption']);
                            $set('name', app(TikTokBlogImporter::class)->titleFromCaption($meta['caption'] ?: 'TikTok post'));
                        }
                    }),
                Forms\Components\Hidden::make('tiktok_caption')->dehydrated(false),
                Forms\Components\Hidden::make('tiktok_author')->dehydrated(false),
                Forms\Components\Hidden::make('tiktok_handle')->dehydrated(false),
                Forms\Components\Hidden::make('tiktok_error')->dehydrated(false),
                Forms\Components\Placeholder::make('tiktok_bot')
                    ->label('')
                    ->visible(fn (Get $get): bool => filled($get('tiktok_caption')) || filled($get('tiktok_error')))
                    ->content(fn (Get $get) => new \Illuminate\Support\HtmlString(view('filament.tiktok-bot-prompt', [
                        'caption' => $get('tiktok_caption'),
                        'author' => $get('tiktok_author'),
                        'handle' => $get('tiktok_handle') ?: $get('account'),
                        'error' => $get('tiktok_error'),
                    ])->render())),
                Forms\Components\Toggle::make('post_to_website')
                    ->label('Can I post this link on the website?')
                    ->helperText('Yes = the blog lists this TikTok. Visitors tap it and go to the original video.')
                    ->default(true)
                    ->live(),
                Forms\Components\Toggle::make('use_exact_caption')
                    ->label('Can I use the exact caption from that TikTok?')
                    ->helperText('Yes = the blog shows the same words you used on TikTok.')
                    ->default(true)
                    ->live()
                    ->visible(fn (Get $get): bool => (bool) $get('post_to_website'))
                    ->afterStateUpdated(function (?bool $state, Set $set, Get $get): void {
                        if ($state && filled($get('tiktok_caption'))) {
                            $set('caption', $get('tiktok_caption'));
                            $set('name', app(TikTokBlogImporter::class)->titleFromCaption((string) $get('tiktok_caption')));
                        }
                    }),
                Forms\Components\TextInput::make('name')
                    ->label('Title on the website')
                    ->maxLength(255)
                    ->visible(fn (Get $get): bool => (bool) $get('post_to_website')),
                Forms\Components\Textarea::make('caption')
                    ->label(fn (Get $get): string => $get('use_exact_caption')
                        ? 'Caption (from TikTok)'
                        : 'Your caption for the website')
                    ->rows(5)
                    ->visible(fn (Get $get): bool => (bool) $get('post_to_website')),
            ])
            ->action(function (array $data): void {
                try {
                    $post = app(TikTokBlogImporter::class)->import([
                        'account' => $data['account'] ?? null,
                        'video_url' => $data['video_url'] ?? '',
                        'post_to_website' => (bool) ($data['post_to_website'] ?? false),
                        'use_exact_caption' => (bool) ($data['use_exact_caption'] ?? false),
                        'caption' => $data['caption'] ?? null,
                        'name' => $data['name'] ?? null,
                    ]);
                } catch (RuntimeException $exception) {
                    Notification::make()
                        ->title($exception->getMessage())
                        ->danger()
                        ->send();

                    return;
                }

                if ($post === null) {
                    Notification::make()
                        ->title('Account saved. Nothing was posted to the blog.')
                        ->success()
                        ->send();

                    return;
                }

                Notification::make()
                    ->title('TikTok is on the blog')
                    ->body($post->name)
                    ->success()
                    ->actions([
                        \Filament\Notifications\Actions\Action::make('edit')
                            ->label('Edit post')
                            ->url(BlogPostResource::getUrl('edit', ['record' => $post])),
                    ])
                    ->send();
            });
    }
}
