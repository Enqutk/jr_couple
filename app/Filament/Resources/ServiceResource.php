<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use App\Filament\Concerns\AuthorizesWithPermission;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Enums\StatusEnum;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Set;
use Illuminate\Support\Str;

class ServiceResource extends Resource
{
    use AuthorizesWithPermission;

    protected static string $permissionKey = 'service';
    protected static ?string $model = Service::class;
    protected static ?string $navigationGroup = 'Website';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Services';
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Service details')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('Used in the public URL: /services/your-slug'),
                        Forms\Components\Textarea::make('short_description')
                            ->label('Card summary')
                            ->helperText('Short text on service cards (homepage & listing).')
                            ->rows(3)
                            ->maxLength(2000)
                            ->nullable()
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('description')
                            ->label('Full description')
                            ->columnSpanFull()
                            ->toolbarButtons(self::getRichEditorToolbarButtons())
                            ->nullable(),
                        Forms\Components\RichEditor::make('features')
                            ->label('Key features')
                            ->columnSpanFull()
                            ->toolbarButtons(self::getRichEditorToolbarButtons()),
                        Forms\Components\Textarea::make('quote')
                            ->label('Highlight quote')
                            ->rows(2)
                            ->maxLength(65535)
                            ->nullable()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Images')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('main_image')
                            ->label('Card / listing image')
                            ->collection('main_image')
                            ->image()
                            ->imagePreviewHeight(150),
                        SpatieMediaLibraryFileUpload::make('secondary_image')
                            ->label('Detail page image')
                            ->collection('secondary_image')
                            ->image()
                            ->imagePreviewHeight(150),
                        SpatieMediaLibraryFileUpload::make('svg')
                            ->label('Optional SVG icon')
                            ->collection('svg')
                            ->acceptedFileTypes(['image/svg+xml'])
                            ->imagePreviewHeight(100),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Publishing')
                    ->schema([
                        Forms\Components\TextInput::make('order')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->required()
                            ->helperText('Lower numbers appear first.'),
                        Forms\Components\Select::make('status')
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
                SpatieMediaLibraryImageColumn::make('main_image')
                    ->collection('main_image')
                    ->label('Image')
                    ->size(48),
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('short_description')->label('Summary')->limit(40)->toggleable(),
                Tables\Columns\TextColumn::make('order')->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (StatusEnum $state): string => match ($state) {
                        StatusEnum::active => 'success',
                        StatusEnum::inactive => 'danger',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->reorderable('order')
            ->filters([
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

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }

    private static function getRichEditorToolbarButtons(): array
    {
        return [
            'bold',
            'italic',
            'underline',
            'strike',
            'link',
            'bulletList',
            'orderedList',
            'h2',
            'h3',
            'h4',
            'blockquote',
        ];
    }
}
