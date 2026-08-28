<?php

namespace App\Filament\Resources;

use App\Enums\StatusEnum;
use App\Filament\Resources\OrganizationContactResource\Pages;
use App\Models\Organization;
use App\Models\OrganizationContact;
use Filament\Forms;
use Filament\Forms\Form;
use App\Filament\Concerns\AuthorizesWithPermission;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrganizationContactResource extends Resource
{
    use AuthorizesWithPermission;

    protected static string $permissionKey = 'organization';
    protected static ?string $model = OrganizationContact::class;
    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 4;

    protected static ?string $navigationIcon = 'heroicon-o-phone';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->label('Contact Type')
                    ->required()
                    ->options(OrganizationContact::getTypeOptions()),
                Forms\Components\TextInput::make('value')
                    ->label('Contact Value')
                    ->required()
                    ->email(fn (\Filament\Forms\Get $get) => $get('type') === 'email')
                    ->tel(fn (\Filament\Forms\Get $get) => $get('type') === 'phone')
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options(StatusEnum::class)
                    ->default(StatusEnum::active),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label('Contact Type')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('value')
                    ->label('Contact Value')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->color(fn(StatusEnum $state): string => match ($state) {
                        StatusEnum::active => 'success',
                        StatusEnum::inactive => 'danger',
                    })->sortable(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListOrganizationContacts::route('/'),
            'create' => Pages\CreateOrganizationContact::route('/create'),
            'edit' => Pages\EditOrganizationContact::route('/{record}/edit'),
        ];
    }
}
