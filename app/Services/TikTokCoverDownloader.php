<?php

namespace App\Services;

use Spatie\MediaLibrary\HasMedia;

class TikTokCoverDownloader
{
    public const USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36';

    public function download(string $thumbnailUrl): ?string
    {
        $thumbnailUrl = trim($thumbnailUrl);

        if ($thumbnailUrl === '' || ! filter_var($thumbnailUrl, FILTER_VALIDATE_URL)) {
            return null;
        }

        $response = \Illuminate\Support\Facades\Http::timeout(20)
            ->withHeaders([
                'User-Agent' => self::USER_AGENT,
                'Referer' => 'https://www.tiktok.com/',
                'Accept' => 'image/avif,image/webp,image/apng,image/*,*/*;q=0.8',
            ])
            ->get($thumbnailUrl);

        if (! $response->successful() || $response->body() === '') {
            return null;
        }

        $path = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR)
            .DIRECTORY_SEPARATOR
            .'tiktok-cover-'.uniqid('', true).'.jpg';

        if (file_put_contents($path, $response->body()) === false) {
            return null;
        }

        return $path;
    }

    public function attach(HasMedia $model, string $thumbnailUrl, string $collection = 'image'): bool
    {
        $path = $this->download($thumbnailUrl);

        if ($path === null) {
            return false;
        }

        try {
            $model->addMedia($path)->toMediaCollection($collection);

            return true;
        } catch (\Throwable) {
            return false;
        } finally {
            if (is_file($path)) {
                @unlink($path);
            }
        }
    }
}
