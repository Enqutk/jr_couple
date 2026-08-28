<?php

namespace App\Filament\Resources;

use App\Enums\StatusEnum;
use App\Filament\Concerns\AuthorizesWithPermission;
use App\Filament\Resources\ProductReviewResource\Pages;
use App\Models\ProductReview;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductReviewResource extends Resource
{
    use AuthorizesWithPermission;

    protected static string $permissionKey = 'entity';

    protected static ?string $model = ProductReview::class;

    protected static ?string $navigationGroup = 'Website';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationLabel = 'Product reviews';

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('author_name')->required()->maxLength(120),
            Forms\Components\TextInput::make('rating')->numeric()->minValue(1)->maxValue(5)->required(),
            Forms\Components\Textarea::make('body')->required()->rows(4),
            Forms\Components\Select::make('status')->options(StatusEnum::class)->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('author_name')->searchable(),
                Tables\Columns\TextColumn::make('rating')->sortable(),
                Tables\Columns\TextColumn::make('body')->limit(50),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductReviews::route('/'),
            'edit' => Pages\EditProductReview::route('/{record}/edit'),
        ];
    }
}
