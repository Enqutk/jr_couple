<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TikTokOEmbedService
{
    public function isTikTokUrl(?string $url): bool
    {
        $host = strtolower((string) parse_url((string) $url, PHP_URL_HOST));
        $host = preg_replace('/^www\./', '', $host) ?: '';

        return str_contains($host, 'tiktok');
    }

    public function normalizeHandle(?string $value): ?string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        if (str_contains($value, 'tiktok.com') && preg_match('~tiktok\.com/@([^/?#]+)~i', $value, $matches)) {
            return $this->cleanHandle($matches[1]);
        }

        return $this->cleanHandle($value);
    }

    public function accountUrl(string $handle): string
    {
        return 'https://www.tiktok.com/@'.$this->cleanHandle($handle);
    }

    public function handleFromVideoUrl(?string $url): ?string
    {
        if (! preg_match('~tiktok\.com/@([^/?#]+)~i', (string) $url, $matches)) {
            return null;
        }

        return $this->cleanHandle($matches[1]);
    }

    /**
     * @return array{caption: string, author_name: string, author_url: string, thumbnail_url: string, handle: ?string}|null
     */
    public function fetch(?string $videoUrl): ?array
    {
        $videoUrl = trim((string) $videoUrl);

        if ($videoUrl === '' || ! $this->isTikTokUrl($videoUrl)) {
            return null;
        }

        $response = Http::timeout(10)
            ->withHeaders([
                'User-Agent' => TikTokCoverDownloader::USER_AGENT,
                'Referer' => 'https://www.tiktok.com/',
            ])
            ->acceptJson()
            ->get('https://www.tiktok.com/oembed', [
                'url' => $videoUrl,
            ]);

        if (! $response->successful()) {
            return null;
        }

        $data = $response->json();

        if (! is_array($data)) {
            return null;
        }

        $caption = trim((string) ($data['title'] ?? ''));
        $authorUrl = (string) ($data['author_url'] ?? '');

        return [
            'caption' => $caption,
            'author_name' => (string) ($data['author_name'] ?? ''),
            'author_url' => $authorUrl,
            'thumbnail_url' => (string) ($data['thumbnail_url'] ?? ''),
            'handle' => $this->handleFromVideoUrl($videoUrl) ?? $this->normalizeHandle($authorUrl),
        ];
    }

    private function cleanHandle(string $handle): string
    {
        $handle = ltrim(trim($handle), '@');

        return preg_replace('/[^\w.]/', '', $handle) ?: $handle;
    }
}
