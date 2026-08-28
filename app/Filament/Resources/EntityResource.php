<?php

namespace App\Filament\Resources;

use App\Enums\EntityTypeEnum;
use App\Enums\StatusEnum;
use App\Filament\Concerns\AuthorizesWithPermission;
use App\Filament\Resources\EntityResource\Pages;
use App\Models\Entity;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EntityResource extends Resource
{
    use AuthorizesWithPermission;

    protected static string $permissionKey = 'entity';

    protected static ?string $model = Entity::class;

    protected static ?string $navigationGroup = 'Store';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Store products';

    protected static ?string $modelLabel = 'Store product';

    protected static ?string $pluralModelLabel = 'Store products';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Entity details')
                    ->description('Use type “Store product” (category = service slug). Blog posts are managed under Blog posts.')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Select::make('type')
                            ->options(collect(EntityTypeEnum::options())->except(EntityTypeEnum::post->value)->all())
                            ->required()
                            ->live(),
                        TextInput::make('category')
                            ->maxLength(120)
                            ->nullable()
                            ->visible(fn ($get) => in_array($get('type'), [
                                EntityTypeEnum::project->value,
                                EntityTypeEnum::product->value,
                            ], true))
                            ->helperText('Products: use service slug (jr-ketema, jr-mobile, jr-real-estate, ruties-hair).'),
                        TextInput::make('link')
                            ->label('External URL')
                            ->maxLength(2048)
                            ->url()
                            ->nullable(),
                        Textarea::make('description')
                            ->rows(5)
                            ->maxLength(65535)
                            ->nullable()
                            ->columnSpanFull(),
                        TextInput::make('price')
                            ->label('Price (ETB)')
                            ->numeric()
                            ->minValue(0)
                            ->nullable()
                            ->visible(fn ($get) => $get('type') === EntityTypeEnum::product->value),
                        TextInput::make('price_label')
                            ->label('Price label')
                            ->placeholder('e.g. /month, per sqm')
                            ->maxLength(60)
                            ->visible(fn ($get) => $get('type') === EntityTypeEnum::product->value),
                        Toggle::make('is_negotiable')
                            ->label('Negotiable price')
                            ->visible(fn ($get) => $get('type') === EntityTypeEnum::product->value),
                        SpatieMediaLibraryFileUpload::make('image')
                            ->label('Image')
                            ->collection('image')
                            ->image()
                            ->imagePreviewHeight('180')
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
                        Toggle::make('is_featured')
                            ->label('Feature on store hero')
                            ->helperText('Only one product can be promoted on the store hero at a time.')
                            ->visible(fn ($get) => $get('type') === EntityTypeEnum::product->value),
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
                TextColumn::make('type')->badge()->sortable(),
                TextColumn::make('category')->toggleable(),
                TextColumn::make('price')
                    ->label('ETB')
                    ->money('ETB')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('order')->sortable(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Store hero')
                    ->boolean()
                    ->toggleable(),
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
                Tables\Filters\SelectFilter::make('type')
                    ->options(collect(EntityTypeEnum::options())->except(EntityTypeEnum::post->value)->all()),
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('type', '!=', EntityTypeEnum::post)
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEntities::route('/'),
            'create' => Pages\CreateEntity::route('/create'),
            'edit' => Pages\EditEntity::route('/{record}/edit'),
        ];
    }
}
