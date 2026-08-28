<?php

namespace App\Filament\Resources;

use App\Enums\StatusEnum;
use App\Filament\Resources\TeamResource\Pages;
use App\Models\Team;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use App\Filament\Concerns\AuthorizesWithPermission;
use Filament\Resources\Resource;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class TeamResource extends Resource
{
    use AuthorizesWithPermission;

    protected static string $permissionKey = 'team';
    protected static ?string $model = Team::class;
    protected static ?string $navigationGroup = 'Website';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationLabel = 'Team';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Profile')
                    ->schema([
                        TextInput::make('first_name')
                            ->label('First name')
                            ->required()
                            ->maxLength(120),
                        TextInput::make('last_name')
                            ->label('Last name')
                            ->maxLength(120),
                        TextInput::make('title')
                            ->label('Role / title')
                            ->maxLength(190)
                            ->required(),
                        Textarea::make('description')
                            ->label('Short bio')
                            ->rows(4)
                            ->columnSpanFull(),
                        SpatieMediaLibraryFileUpload::make('image')
                            ->label('Photo')
                            ->collection('team-images')
                            ->image()
                            ->imagePreviewHeight(180)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Publishing')
                    ->schema([
                        TextInput::make('order')
                            ->numeric()
                            ->default(1)
                            ->minValue(0)
                            ->required()
                            ->helperText('Lower numbers appear first.'),
                        Select::make('status')
                            ->options(StatusEnum::class)
                            ->default(StatusEnum::active)
                            ->required(),
                        Toggle::make('founder')
                            ->label('Show founder badge'),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('image')
                    ->collection('team-images')
                    ->circular()
                    ->size(48),
                TextColumn::make('first_name')->searchable()->sortable(),
                TextColumn::make('last_name')->searchable()->sortable(),
                TextColumn::make('title')->label('Role')->searchable(),
                IconColumn::make('founder')->boolean(),
                TextColumn::make('order')->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (StatusEnum $state): string => match ($state) {
                        StatusEnum::active => 'success',
                        StatusEnum::inactive => 'danger',
                    })
                    ->sortable(),
            ])
            ->reorderable('order')
            ->defaultSort('order')
            ->filters([
                Tables\Filters\TernaryFilter::make('founder')->label('Founder')->boolean(),
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('status')->options(StatusEnum::class),
            ])
            ->actions([
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeams::route('/'),
            'create' => Pages\CreateTeam::route('/create'),
            'edit' => Pages\EditTeam::route('/{record}/edit'),
        ];
    }
}
