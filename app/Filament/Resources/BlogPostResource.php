<?php

namespace App\Filament\Resources;

use App\Enums\EntityTypeEnum;
use App\Enums\PostSourceEnum;
use App\Enums\StatusEnum;
use App\Filament\Concerns\AuthorizesWithPermission;
use App\Filament\Resources\BlogPostResource\Pages;
use App\Models\Entity;
use App\Services\TikTokBlogImporter;
use App\Services\TikTokOEmbedService;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BlogPostResource extends Resource
{
    use AuthorizesWithPermission;

    protected static string $permissionKey = 'blog';

    protected static ?string $model = Entity::class;

    protected static ?string $navigationGroup = 'Website';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Blog posts';

    protected static ?string $modelLabel = 'Blog post';

    protected static ?string $pluralModelLabel = 'Blog posts';

    protected static ?string $slug = 'blog-posts';

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('How this post is shared')
                    ->description('Social posts send visitors to TikTok, Telegram, Instagram, or another link. Hosted posts keep the photo or video on this website.')
                    ->schema([
                        Radio::make('source')
                            ->label('Post type')
                            ->options(PostSourceEnum::options())
                            ->descriptions([
                                PostSourceEnum::social->value => 'Paste a social link and a short description. Opening the post redirects to that page.',
                                PostSourceEnum::media->value => 'Upload a photo or video here. It is published on the blog page.',
                            ])
                            ->default(PostSourceEnum::media->value)
                            ->required()
                            ->live()
                            ->columnSpanFull(),
                    ]),

                Section::make('Post content')
                    ->schema([
                        TextInput::make('name')
                            ->label('Title')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('category')
                            ->label('Topic')
                            ->maxLength(120)
                            ->nullable()
                            ->helperText('Optional label such as JR Ketema or Tips.'),
                        TextInput::make('link')
                            ->label('Social media URL')
                            ->url()
                            ->maxLength(2048)
                            ->required(fn ($get) => static::sourceIs($get, PostSourceEnum::social))
                            ->visible(fn ($get) => static::sourceIs($get, PostSourceEnum::social))
                            ->live(onBlur: true)
                            ->helperText('TikTok, Telegram, Instagram, YouTube, or any public post URL. For TikTok, paste a video link and we will offer the original caption.')
                            ->afterStateUpdated(function (?string $state, Set $set, Get $get): void {
                                static::fillTikTokMeta($state, $set, $get);
                            })
                            ->columnSpanFull(),
                        Hidden::make('tiktok_caption')->dehydrated(false),
                        Hidden::make('tiktok_author')->dehydrated(false),
                        Hidden::make('tiktok_handle')->dehydrated(false),
                        Hidden::make('tiktok_error')->dehydrated(false),
                        Placeholder::make('tiktok_bot')
                            ->label('')
                            ->columnSpanFull()
                            ->visible(fn ($get) => static::sourceIs($get, PostSourceEnum::social)
                                && (filled($get('tiktok_caption')) || filled($get('tiktok_error'))))
                            ->content(fn ($get) => new \Illuminate\Support\HtmlString(view('filament.tiktok-bot-prompt', [
                                'caption' => $get('tiktok_caption'),
                                'author' => $get('tiktok_author'),
                                'handle' => $get('tiktok_handle'),
                                'error' => $get('tiktok_error'),
                            ])->render())),
                        Toggle::make('use_exact_caption')
                            ->label('Can I use the exact caption from that TikTok?')
                            ->helperText('Turn on to keep the same words as the TikTok. Turn off to write your own below.')
                            ->default(true)
                            ->live()
                            ->dehydrated(false)
                            ->columnSpanFull()
                            ->visible(fn ($get) => static::sourceIs($get, PostSourceEnum::social) && filled($get('tiktok_caption')))
                            ->afterStateUpdated(function (?bool $state, Set $set, Get $get): void {
                                if ($state && filled($get('tiktok_caption'))) {
                                    $set('description', $get('tiktok_caption'));
                                }
                            }),
                        Textarea::make('description')
                            ->rows(6)
                            ->maxLength(65535)
                            ->nullable()
                            ->columnSpanFull()
                            ->helperText('Shown on the blog listing. For hosted posts this is also the article text.'),
                        SpatieMediaLibraryFileUpload::make('image')
                            ->label('Optional cover image')
                            ->collection('image')
                            ->image()
                            ->imagePreviewHeight('180')
                            ->visible(fn ($get) => static::sourceIs($get, PostSourceEnum::social))
                            ->helperText('Optional thumbnail on the blog card. Visitors still go to the original social post.')
                            ->columnSpanFull(),
                        SpatieMediaLibraryFileUpload::make('post_media')
                            ->label('Photo or video')
                            ->collection('post_media')
                            ->multiple()
                            ->reorderable()
                            ->acceptedFileTypes([
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                                'image/gif',
                                'video/mp4',
                                'video/webm',
                                'video/quicktime',
                            ])
                            ->maxSize(102400)
                            ->required(fn ($get) => static::sourceIs($get, PostSourceEnum::media))
                            ->visible(fn ($get) => static::sourceIs($get, PostSourceEnum::media))
                            ->helperText('Upload one or more photos or videos (max 100MB each). These stay on this website.')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Publishing')
                    ->schema([
                        TextInput::make('order')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->required()
                            ->helperText('Lower numbers appear first.'),
                        Select::make('status')
                            ->options(StatusEnum::class)
                            ->default(StatusEnum::active)
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('image')
                    ->collection('image')
                    ->size(48),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('source')
                    ->badge()
                    ->formatStateUsing(fn (?PostSourceEnum $state): string => match ($state) {
                        PostSourceEnum::social => 'Social link',
                        PostSourceEnum::media => 'Hosted media',
                        default => 'Hosted media',
                    })
                    ->color(fn (?PostSourceEnum $state): string => match ($state) {
                        PostSourceEnum::social => 'info',
                        default => 'success',
                    }),
                TextColumn::make('category')->toggleable(),
                TextColumn::make('link')
                    ->label('Social URL')
                    ->limit(32)
                    ->url(fn (Entity $record): ?string => $record->socialRedirectUrl())
                    ->openUrlInNewTab()
                    ->toggleable(),
                TextColumn::make('order')->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (StatusEnum $state): string => match ($state) {
                        StatusEnum::active => 'success',
                        StatusEnum::inactive => 'danger',
                    })
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->reorderable('order')
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('source')
                    ->options(PostSourceEnum::options()),
                Tables\Filters\SelectFilter::make('status')
                    ->options(StatusEnum::class),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('order');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('type', EntityTypeEnum::post)
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogPosts::route('/'),
            'create' => Pages\CreateBlogPost::route('/create'),
            'edit' => Pages\EditBlogPost::route('/{record}/edit'),
        ];
    }

    protected static function sourceIs($get, PostSourceEnum $expected): bool
    {
        $value = $get('source');

        if ($value instanceof PostSourceEnum) {
            return $value === $expected;
        }

        return $value === $expected->value;
    }

    protected static function fillTikTokMeta(?string $state, Set $set, Get $get): void
    {
        $set('tiktok_caption', null);
        $set('tiktok_author', null);
        $set('tiktok_handle', null);
        $set('tiktok_error', null);

        if (! static::sourceIs($get, PostSourceEnum::social)) {
            return;
        }

        $oembed = app(TikTokOEmbedService::class);

        if (! $oembed->isTikTokUrl($state)) {
            return;
        }

        $meta = $oembed->fetch($state);

        if ($meta === null) {
            $set('tiktok_error', 'Could not read that TikTok. Make sure the video is public.');

            return;
        }

        $set('tiktok_caption', $meta['caption']);
        $set('tiktok_author', $meta['author_name']);
        $set('tiktok_handle', $meta['handle']);
        $set('use_exact_caption', true);
        $set('description', $meta['caption']);

        if (blank($get('name')) && filled($meta['caption'])) {
            $set('name', app(TikTokBlogImporter::class)->titleFromCaption($meta['caption']));
        }
    }
}
