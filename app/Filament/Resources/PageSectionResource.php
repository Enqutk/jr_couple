<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageSectionResource\Pages;
use App\Models\PageSection;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use App\Filament\Concerns\AuthorizesWithPermission;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PageSectionResource extends Resource
{
    use AuthorizesWithPermission;

    protected static string $permissionKey = 'page';
    protected static ?string $model = PageSection::class;
    protected static ?string $navigationGroup = 'Content';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Page Sections';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Section Information')
                    ->schema([
                        Forms\Components\Select::make('page_id')
                            ->label('Page')
                            ->options(Page::active()->pluck('title', 'id'))
                            ->required()
                            ->searchable()
                            ->preload(),
                        
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\Textarea::make('subtitle')
                            ->maxLength(500)
                            ->nullable(),
                    ])->columns(2),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Only active sections will be visible to visitors'),
                        
                        Forms\Components\TextInput::make('display_order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Order for display (lower numbers appear first)'),
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
                
                Tables\Columns\TextColumn::make('page.title')
                    ->label('Page')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('subtitle')
                    ->limit(50)
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('content_blocks_count')
                    ->counts('contentBlocks')
                    ->label('Content Blocks')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('display_order')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                
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
                Tables\Filters\SelectFilter::make('page_id')
                    ->label('Page')
                    ->options(Page::active()->pluck('title', 'id'))
                    ->searchable()
                    ->preload(),
                
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('All Sections')
                    ->trueLabel('Active Sections')
                    ->falseLabel('Inactive Sections'),
                
                Tables\Filters\Filter::make('has_content_blocks')
                    ->query(fn ($query) => $query->has('contentBlocks'))
                    ->label('Has Content Blocks'),
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
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['page', 'contentBlocks']));
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
            'index' => Pages\ListPageSections::route('/'),
            'create' => Pages\CreatePageSection::route('/create'),
            'edit' => Pages\EditPageSection::route('/{record}/edit'),
        ];
    }
}
