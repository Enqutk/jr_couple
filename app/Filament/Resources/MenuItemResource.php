<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuItemResource\Pages;
use App\Models\MenuItem;
use Filament\Forms;
use Filament\Forms\Form;
use App\Filament\Concerns\AuthorizesWithPermission;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MenuItemResource extends Resource
{
    use AuthorizesWithPermission;

    protected static string $permissionKey = 'menu';
    protected static ?string $model = MenuItem::class;
    protected static ?string $navigationGroup = 'Menus';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Menu Items';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('icon')
                    ->maxLength(100),
                Forms\Components\Select::make('menu_id')
                    ->relationship('menu', 'name')
                    ->required()
                    ->label('Menu Location'),

                Forms\Components\Select::make('parent_id')
                    ->relationship('parent', 'title')
                    ->label('Parent Menu Item')
                    ->nullable(),
                Forms\Components\Select::make('link_type')
                    ->options([
                        'internal' => 'Internal',
                        'external' => 'External',
                    ])
                    ->default('internal')
                    ->reactive(),
                Forms\Components\TextInput::make('url')
                    ->requiredIf('link_type', 'external')
                    ->url()
                    ->maxLength(500),
                Forms\Components\Select::make('target')
                    ->options([
                        '_self' => 'Self',
                        '_blank' => 'Blank',
                    ])
                    ->default('_self'),
                Forms\Components\TextInput::make('order_number')
                    ->numeric()
                    ->minValue(1)
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->formatStateUsing(function ($state, $record) {
                        $indent = $record->parent ? '  ⤷ ' : '';
                        return $indent . $state;
                    })
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('parent.title')
                    ->label('Parent')
                    ->default('')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('menu.name')
                    ->label('Menu Location')
                    ->default('N/A')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('link_type')
                    ->label('Link Type'),
                Tables\Columns\TextColumn::make('url')
                    ->label('URL'),
                Tables\Columns\TextColumn::make('target')
                    ->label('Target'),
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Order Number')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('menu_id')
                    ->relationship('menu', 'name')
                    ->label('Menu Location'),
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
            'index' => Pages\ListMenuItems::route('/'),
            'create' => Pages\CreateMenuItem::route('/create'),
            'edit' => Pages\EditMenuItem::route('/{record}/edit'),
        ];
    }
}
