<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HeroResource\Pages;
use App\Models\Hero;
use Filament\Forms;
use Filament\Forms\Form;
use App\Filament\Concerns\AuthorizesWithPermission;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Enums\StatusEnum;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;

class HeroResource extends Resource
{
    use AuthorizesWithPermission;

    protected static string $permissionKey = 'hero';
    protected static ?string $model = Hero::class;
    protected static ?string $navigationGroup = 'Website';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Hero slides';
    protected static ?string $modelLabel = 'Hero slide';
    protected static ?string $pluralModelLabel = 'Hero slides';
    protected static ?string $navigationIcon = 'heroicon-o-photo';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Slide content')
                    ->description('Shown on the homepage hero. The company name from Organization settings always appears above the headline.')
                    ->schema([
                        Forms\Components\TextInput::make('subtitle')
                            ->label('Eyebrow')
                            ->placeholder('e.g. Grass · Mobile · Hair')
                            ->helperText('Small label above the brand.')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('title')
                            ->label('Headline')
                            ->required()
                            ->placeholder('e.g. Water systems that feed people')
                            ->helperText('Supporting headline under the brand name.')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label('Supporting text')
                            ->required()
                            ->rows(4)
                            ->helperText('One short paragraph under the headline.'),
                        Forms\Components\SpatieMediaLibraryFileUpload::make('image')
                            ->label('Hero image')
                            ->collection('image')
                            ->image()
                            ->imageEditor()
                            ->imagePreviewHeight('200')
                            ->required()
                            ->helperText('Use a wide photo (about 1200×900 or larger).'),
                    ])
                    ->columns(1),

                Forms\Components\Section::make('Call to action')
                    ->schema([
                        Forms\Components\TextInput::make('text_link')
                            ->label('Button label')
                            ->placeholder('e.g. Explore services')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('button_link')
                            ->label('Button URL')
                            ->placeholder('/our-services or https://…')
                            ->maxLength(2048)
                            ->helperText('Internal path (/our-services) or full URL.'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Publishing')
                    ->schema([
                        Forms\Components\TextInput::make('order')
                            ->label('Display order')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->required()
                            ->helperText('Lower numbers appear first in the carousel.'),
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
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')
                    ->collection('image')
                    ->square()
                    ->size(56),
                Tables\Columns\TextColumn::make('title')
                    ->label('Headline')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subtitle')
                    ->label('Eyebrow')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('text_link')
                    ->label('Button label')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('button_link')
                    ->label('Button URL')
                    ->limit(30)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('order')
                    ->sortable(),
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
                Tables\Filters\TrashedFilter::make(),
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
        return parent::getEloquentQuery()->withoutGlobalScopes([
            SoftDeletingScope::class,
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHeroes::route('/'),
            'create' => Pages\CreateHero::route('/create'),
            'edit' => Pages\EditHero::route('/{record}/edit'),
        ];
    }
}
