<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class QuickLinksWidget extends Widget
{
    protected static string $view = 'filament.widgets.quick-links';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';
}
