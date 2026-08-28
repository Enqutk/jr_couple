<?php

namespace App\Services;

use App\Enums\StatusEnum;
use App\Models\ContentBlock;
use App\Models\Organization;
use App\Models\OrganizationContact;
use Illuminate\Support\Str;

class HomeContentService
{
    public function getHomeContent(): array
    {
        $organization = Organization::query()->with('media')->first();

        $contacts = OrganizationContact::where('status', StatusEnum::active)
            ->select(['type', 'value'])
            ->get()
            ->groupBy('type');

        $blocks = ContentBlock::where('is_active', true)
            ->whereIn('slug', [
                'key-features',
                'veritas-afrika-co-ltd',
                'about-section-1',
                'about-section-2',
                'video-section',
                'video-thumbnail',
                'video-details',
                'stats',
            ])
            ->select(['id', 'slug', 'short_description', 'content', 'list_items', 'metadata', 'title', 'subtitle', 'icon', 'video_url'])
            ->with('media')
            ->get()
            ->keyBy('slug');

        $aboutFeaturesBlock = $blocks->get('veritas-afrika-co-ltd');
        $aboutSection1Block = $blocks->get('about-section-1');
        $aboutSection2Block = $blocks->get('about-section-2');
        $statsBlock = $blocks->get('stats');

        $siteName = $organization?->title ?: config('app.name', 'Site');

        if ($organization?->tagline) {
            $tagline = $organization->tagline;
        } elseif ($aboutFeaturesBlock?->short_description) {
            $tagline = $aboutFeaturesBlock->short_description;
        } else {
            $tagline = Str::limit(trim(strip_tags((string) ($aboutFeaturesBlock?->content ?? ''))), 160) ?: '';
        }

        $metaDescription = $organization?->meta_description
            ?: ($tagline !== '' ? "{$siteName} — {$tagline}" : $siteName);

        $theme = $organization
            ? $organization->resolvedTheme()
            : array_merge(Organization::defaultTheme(), [
                'accent_soft' => Organization::hexToRgba(Organization::defaultTheme()['accent'], 0.12),
            ]);

        $aboutImage = $aboutFeaturesBlock?->getFirstMediaUrl('images') ?: null;

        return [
            'siteName' => $siteName,
            'tagline' => $tagline,
            'metaDescription' => $metaDescription,
            'theme' => $theme,
            'payment' => $organization
                ? $organization->resolvedPayment()
                : Organization::defaultPayment(),
            'logoUrl' => $organization?->logo_url,
            'faviconUrl' => $organization?->favicon_url,

            'email' => $contacts->get('email', collect())->pluck('value')->all(),
            'phone' => $contacts->get('phone', collect())->pluck('value')->all(),
            'fax' => $contacts->get('fax', collect())->pluck('value')->all(),
            'address' => $organization->address ?? null,
            'working_days' => $organization->opening_hours ?? [],
            'hours' => $this->formatOpeningHours($organization?->opening_hours ?? []),
            'map' => $organization->map_url ?? null,

            'heroFeatures' => $blocks->get('key-features'),
            'aboutFeatures' => $aboutFeaturesBlock
                ? [
                    'image' => $aboutImage,
                    'title' => $aboutFeaturesBlock->title,
                    'subtitle' => $aboutFeaturesBlock->subtitle,
                    'description' => html_entity_decode((string) $aboutFeaturesBlock->content),
                ]
                : null,

            'aboutSection1' => $aboutSection1Block
                ? [
                    'image' => $aboutSection1Block->getFirstMediaUrl('images') ?: null,
                    'title' => $aboutSection1Block->title,
                    'subtitle' => $aboutSection1Block->subtitle,
                    'description' => html_entity_decode((string) $aboutSection1Block->content),
                ]
                : null,

            'aboutSection2' => $aboutSection2Block
                ? [
                    'image' => $aboutSection2Block->getFirstMediaUrl('images') ?: null,
                    'title' => $aboutSection2Block->title,
                    'subtitle' => $aboutSection2Block->subtitle,
                    'description' => html_entity_decode((string) $aboutSection2Block->content),
                ]
                : null,

            'videoSection' => $blocks->get('video-section'),

            'videoThumbnail' => $blocks->get('video-thumbnail')
                ? ($blocks->get('video-thumbnail')->getFirstMediaUrl('images') ?: null)
                : null,

            'videoDetails' => $blocks->get('video-details') ? [
                'short_description' => $blocks->get('video-details')->short_description,
                'list_items' => $blocks->get('video-details')->list_items,
                'metadata' => $blocks->get('video-details')->metadata,
            ] : null,

            'stats' => $statsBlock?->list_items ?? [],
            'statsTitle' => $statsBlock?->title ?: 'Impact that compounds',
            'statsSubtitle' => $statsBlock?->subtitle ?: 'By the numbers',
        ];
    }

    /**
     * @param  array<int, array{days?: mixed, from?: mixed, to?: mixed}>  $hours
     * @return array<int, array{label: string, from: string, to: string}>
     */
    private function formatOpeningHours(?array $hours): array
    {
        $labels = [
            'mon' => 'Mon', 'tue' => 'Tue', 'wed' => 'Wed', 'thu' => 'Thu',
            'fri' => 'Fri', 'sat' => 'Sat', 'sun' => 'Sun',
        ];

        $formatted = [];

        foreach ($hours ?? [] as $slot) {
            if (empty($slot['days']) || empty($slot['from']) || empty($slot['to'])) {
                continue;
            }

            $days = collect($slot['days'])
                ->map(fn ($day) => $labels[$day] ?? $day)
                ->values()
                ->all();

            if ($days === []) {
                continue;
            }

            $formatted[] = [
                'label' => count($days) === 1
                    ? $days[0]
                    : $days[0].' – '.$days[array_key_last($days)],
                'from' => substr((string) $slot['from'], 0, 5),
                'to' => substr((string) $slot['to'], 0, 5),
            ];
        }

        return $formatted;
    }
}
