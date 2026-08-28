<?php

namespace App\View\Components;

use App\Enums\StatusEnum;
use App\Models\SocialRef;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class SocialMedia extends Component
{
    /** @var Collection<int, SocialRef> */
    public $socialRefs;

    public function __construct()
    {
        $this->socialRefs = SocialRef::where('status', StatusEnum::active)
            ->orderBy('order')
            ->get();
    }

    public function iconClass(?string $class): string
    {
        $value = strtolower((string) $class);

        $map = [
            'x-twitter' => 'bi-twitter-x',
            'twitter' => 'bi-twitter-x',
            'facebook' => 'bi-facebook',
            'instagram' => 'bi-instagram',
            'tiktok' => 'bi-tiktok',
            'youtube' => 'bi-youtube',
            'whatsapp' => 'bi-whatsapp',
            'telegram' => 'bi-telegram',
            'linkedin' => 'bi-linkedin',
        ];

        foreach ($map as $needle => $icon) {
            if (str_contains($value, $needle)) {
                return 'bi '.$icon;
            }
        }

        if (str_contains($value, 'bi-')) {
            return str_starts_with(trim((string) $class), 'bi')
                ? (string) $class
                : 'bi '.(string) $class;
        }

        return 'bi bi-share';
    }

    public function render(): View|Closure|string
    {
        return view('components.social-media', [
            'items' => $this->socialRefs->map(fn (SocialRef $ref) => [
                'title' => $ref->title,
                'link' => $ref->link,
                'icon' => $this->iconClass($ref->icon_class),
            ]),
        ]);
    }
}
