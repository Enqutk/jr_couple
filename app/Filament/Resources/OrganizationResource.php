<?php

namespace App\Filament\Resources;

use App\Enums\StatusEnum;
use App\Filament\Resources\OrganizationResource\Pages;
use App\Models\Organization;
use Filament\Forms;
use Filament\Forms\Form;
use App\Filament\Concerns\AuthorizesWithPermission;
use Filament\Resources\Resource;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class OrganizationResource extends Resource
{
    use AuthorizesWithPermission;

    protected static string $permissionKey = 'organization';
    protected static ?string $model = Organization::class;
    protected static ?string $navigationGroup = 'Setting';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Company name')
                            ->helperText('Shown in the header, footer, and hero. Use a space for accent styling (e.g. “Maji Works”).')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('po_box')
                            ->label('PO Box')
                            ->maxLength(100),
                    ]),
                Forms\Components\TextInput::make('tagline')
                    ->label('Tagline')
                    ->helperText('Short line under the brand in the footer and empty-hero fallback.')
                    ->maxLength(500),
                Forms\Components\Textarea::make('meta_description')
                    ->label('Default SEO description')
                    ->helperText('Used as the site-wide meta description when a page does not set its own.')
                    ->rows(2)
                    ->maxLength(500),
                Forms\Components\Section::make('Brand images')
                    ->description('Upload once — used across the public site. Hero, services, projects, and team images are managed on their own resources.')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('logo')
                            ->label('Logo')
                            ->collection('logo')
                            ->image()
                            ->imageEditor()
                            ->helperText('Shown in the header and footer. PNG or SVG with transparent background works best.'),
                        SpatieMediaLibraryFileUpload::make('favicon')
                            ->label('Favicon')
                            ->collection('favicon')
                            ->image()
                            ->helperText('Browser tab icon (PNG/ICO, square).'),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Brand colors')
                    ->description('These override the public site theme. Leave blank to keep defaults. Change and save, then hard-refresh the site.')
                    ->schema([
                        Forms\Components\ColorPicker::make('theme.accent')
                            ->label('Accent')
                            ->helperText('Buttons, links, highlights'),
                        Forms\Components\ColorPicker::make('theme.accent_dark')
                            ->label('Accent (hover)')
                            ->helperText('Optional — auto-darkened from accent if empty'),
                        Forms\Components\ColorPicker::make('theme.ink')
                            ->label('Text / ink'),
                        Forms\Components\ColorPicker::make('theme.muted')
                            ->label('Muted text'),
                        Forms\Components\ColorPicker::make('theme.bg')
                            ->label('Page background'),
                        Forms\Components\ColorPicker::make('theme.surface')
                            ->label('Cards / surface'),
                        Forms\Components\ColorPicker::make('theme.line')
                            ->label('Borders / lines'),
                        Forms\Components\ColorPicker::make('theme.dark')
                            ->label('Footer / dark blocks'),
                    ])
                    ->columns(2)
                    ->collapsed(false),
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('address')
                            ->label('Address')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('map_url')
                            ->label('Map URL')
                            ->nullable()
                            ->maxLength(4024),
                    ]),
                Forms\Components\Repeater::make('opening_hours')
                    ->label('Opening Hours')
                    ->schema([
                        Forms\Components\Select::make('days')
                            ->label('Days')
                            ->multiple()
                            ->options(Organization::getDayOptions()),
                        Forms\Components\TimePicker::make('from')
                            ->label('From'),
                        Forms\Components\TimePicker::make('to')
                            ->label('To')
                    ])
                    ->columns(3)
                    ->minItems(1)
                    ->addActionLabel('Add Opening Hours'),

                Forms\Components\Select::make('status')
                    ->options(StatusEnum::class)
                    ->default(StatusEnum::active)
                    ->required(),
                Forms\Components\TextInput::make('created_at')
                    ->label('Created At')
                    ->disabled(),
                Forms\Components\TextInput::make('updated_at')
                    ->label('Updated At')
                    ->disabled()
            ]);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\EditOrganization::route('/'),
        ];
    }

      public static function getRecord(): Organization
    {
        // Always return the first organization, creating it if it doesn't exist.
        return Organization::firstOrCreate([], [
            'title' => 'Your Organization',
            'status' => 'active',
        ]);
    }
}
