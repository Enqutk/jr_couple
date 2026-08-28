<?php

namespace App\Filament\Resources;

use App\Enums\MenuLocationEnum;
use App\Filament\Resources\MenuLocationResource\Pages;
use App\Models\MenuLocation;
use Filament\Forms;
use Filament\Forms\Form;
use App\Filament\Concerns\AuthorizesWithPermission;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class MenuLocationResource extends Resource
{
    use AuthorizesWithPermission;

    protected static string $permissionKey = 'menu';
    protected static ?string $model = MenuLocation::class;
    protected static ?string $navigationGroup = 'Menus';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Menu';
    protected static ?string $navigationIcon = 'heroicon-o-bars-3';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(120)
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('slug', Str::slug($state));
                    }),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(120)
                    ->unique(MenuLocation::class, 'slug', ignoreRecord: true)
                    ->readOnly(),
                Forms\Components\Select::make('location')
                    ->label('location')
                    ->options(
                        collect(MenuLocationEnum::cases())
                            ->mapWithKeys(fn($case) => [$case->value => $case->name])
                            ->toArray()
                    )
                    ->default(MenuLocationEnum::Navbar->value)
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->nullable(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('location')
                    ->label('Location')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('location')
                    ->options(
                        collect(MenuLocationEnum::cases())
                            ->mapWithKeys(fn($case) => [$case->value => $case->name])
                            ->toArray()
                    )
                    ->label('Location')

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListMenuLocations::route('/'),
            'create' => Pages\CreateMenuLocation::route('/create'),
            'edit' => Pages\EditMenuLocation::route('/{record}/edit'),
        ];
    }
}
