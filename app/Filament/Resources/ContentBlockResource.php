<?php

namespace App\Filament\Resources;

use App\Enums\ContentTypeEnum;
use App\Filament\Resources\ContentBlockResource\Pages;
use App\Models\ContentBlock;
use App\Models\PageSection;
use Filament\Forms;
use Filament\Forms\Form;
use App\Filament\Concerns\AuthorizesWithPermission;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ContentBlockResource extends Resource
{
    use AuthorizesWithPermission;

    protected static string $permissionKey = 'page';
    protected static ?string $model = ContentBlock::class;
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Content Blocks';
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Content Block Information')
                    ->schema([
                        Forms\Components\Select::make('section_id')
                            ->label('Page Section')
                            ->options(PageSection::active()->with('page')->get()->mapWithKeys(function ($section) {
                                return [$section->id => $section->page->title . ' → ' . $section->title];
                            }))
                            ->required()
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('type')
                            ->options(collect(ContentTypeEnum::cases())->mapWithKeys(fn($case) => [$case->value => ucfirst($case->name)]))
                            ->default(ContentTypeEnum::Text->value)
                            ->label('Type')
                            ->required()
                            ->reactive(),

                        Forms\Components\TextInput::make('title')
                            ->maxLength(255)
                            ->nullable()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('slug', Str::slug($state));
                            }),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(120)
                            ->unique(ContentBlock::class, 'slug', ignoreRecord: true)
                            ->readOnly(),

                        Forms\Components\TextInput::make('icon')
                            ->maxLength(100),

                        Forms\Components\TextInput::make('subtitle')
                            ->maxLength(255)
                            ->nullable(),

                        Forms\Components\Textarea::make('short_description')
                            ->maxLength(500)
                            ->nullable(),
                    ])->columns(2),

                Forms\Components\Section::make('Content')
                    ->schema([
                        // Show a repeater for "List" type to allow dynamic features with icon, title, and description
                        Forms\Components\Repeater::make('list_items')
                            ->label('List items')
                            ->visible(fn (callable $get) => $get('type') === 'list')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('Title (features)')
                                    ->maxLength(255)
                                    ->helperText('Used for feature cards (e.g. key-features).'),
                                Forms\Components\TextInput::make('label')
                                    ->label('Stat label')
                                    ->maxLength(255)
                                    ->helperText('Used for stats counters (e.g. “Projects delivered”).'),
                                Forms\Components\TextInput::make('value')
                                    ->label('Stat number')
                                    ->numeric()
                                    ->helperText('Animate-to value for the stats section.'),
                                Forms\Components\TextInput::make('suffix')
                                    ->label('Stat suffix')
                                    ->placeholder('+')
                                    ->maxLength(10),
                                Forms\Components\TextInput::make('icon')
                                    ->label('Icon class')
                                    ->maxLength(100)
                                    ->helperText('Bootstrap Icons class, e.g. bi bi-shield-check'),
                                Forms\Components\Textarea::make('description')
                                    ->label('Description')
                                    ->maxLength(500)
                                    ->rows(2),
                            ])
                            ->minItems(1)
                            ->columns(2)
                            ->columnSpanFull(),

                        Forms\Components\Tabs::make('Content Input')
                            ->visible(fn(callable $get) => in_array($get('type'), ['text', 'timeline', 'image', 'video', 'gallery', 'list']))
                            ->tabs([
                                Forms\Components\Tabs\Tab::make('Rich Editor')
                                    ->schema([
                                        Forms\Components\RichEditor::make('content')
                                            ->label('Content (WYSIWYG)')
                                            ->nullable()
                                            ->columnSpanFull(),
                                    ]),
                                Forms\Components\Tabs\Tab::make('Raw HTML')
                                    ->schema([
                                        Forms\Components\Textarea::make('content')
                                            ->label('Content (Raw HTML)')
                                            ->rows(8)
                                            ->nullable()
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Forms\Components\SpatieMediaLibraryFileUpload::make('images')
                            ->collection('images')
                            ->multiple()
                            ->image()
                            ->imagePreviewHeight(150)
                            ->visible(fn(callable $get) => in_array($get('type'), ['image', 'gallery']))
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('video_url')
                            ->label('video_url')
                            ->placeholder('Enter video ')
                            ->visible(fn(callable $get) => $get('type') === 'video')
                            ->columnSpanFull(),

                        Forms\Components\KeyValue::make('metadata')
                            ->label('Additional Data')
                            ->keyLabel('Field')
                            ->valueLabel('Value')
                            ->nullable()
                            ->helperText('Store additional structured data for complex blocks')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Only active content blocks will be visible to visitors'),

                        Forms\Components\TextInput::make('display_order')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->unique(ignoreRecord: true)
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('section.page.title')
                    ->label('Page')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('section.title')
                    ->label('Section')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->color(fn(ContentTypeEnum $state): string => match ($state) {
                        ContentTypeEnum::Text => 'primary',
                        ContentTypeEnum::Image => 'info',
                        ContentTypeEnum::Video => 'warning',
                        ContentTypeEnum::List => 'success',
                        ContentTypeEnum::Timeline => 'secondary',
                        ContentTypeEnum::Gallery => 'danger',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('short_description')
                    ->limit(50)
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\SpatieMediaLibraryImageColumn::make('images')
                    ->collection('images')
                    ->label('Images')
                    ->size(40)
                    ->square(),
                Tables\Columns\TextColumn::make('video_url')
                    ->label('video_url')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('display_order')
                    ->sortable()
                    ->label('Order'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('section_id')
                    ->label('Page Section')
                    ->options(PageSection::active()->with('page')->get()->mapWithKeys(function ($section) {
                        return [$section->id => $section->page->title . ' → ' . $section->title];
                    }))
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'text' => 'Text Block',
                        'image' => 'Image Block',
                        'video' => 'Video Block',
                        'list' => 'List Block',
                        'timeline' => 'Timeline Block',
                        'gallery' => 'Gallery Block',
                    ]),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('All Blocks')
                    ->trueLabel('Active Blocks')
                    ->falseLabel('Inactive Blocks'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('display_order')
            ->modifyQueryUsing(fn(Builder $query) => $query->with(['section.page']));
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContentBlocks::route('/'),
            'create' => Pages\CreateContentBlock::route('/create'),
            'edit' => Pages\EditContentBlock::route('/{record}/edit'),
        ];
    }
}
