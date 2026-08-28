<?php

namespace App\Http\Controllers;

use App\Enums\EntityTypeEnum;
use App\Enums\PostSourceEnum;
use App\Enums\StatusEnum;
use App\Models\Entity;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $channel = $request->string('channel')->toString() ?: 'all';
        $search = trim($request->string('q')->toString());

        $base = Entity::query()
            ->where('status', StatusEnum::active)
            ->where('type', EntityTypeEnum::post)
            ->with('media');

        $posts = (clone $base)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', '%'.$search.'%')
                        ->orWhere('description', 'like', '%'.$search.'%')
                        ->orWhere('category', 'like', '%'.$search.'%');
                });
            })
            ->when($channel === 'tiktok', function ($query) {
                $query->where('source', PostSourceEnum::social)
                    ->where('link', 'like', '%tiktok%');
            })
            ->when($channel === 'tips', function ($query) {
                $query->where(function ($inner) {
                    $inner->where('source', PostSourceEnum::media)
                        ->orWhereNull('source');
                });
            })
            ->orderBy('order')
            ->orderByDesc('created_at')
            ->get();

        $featured = $posts->first(fn (Entity $post) => filled($post->firstPostMediaUrl()))
            ?? $posts->first();

        $grid = $featured
            ? $posts->reject(fn (Entity $post) => $post->id === $featured->id)->values()
            : $posts;

        $counts = $this->channelCounts((clone $base)->get());

        return view('blog.index', compact('posts', 'featured', 'grid', 'channel', 'search', 'counts'));
    }

    public function show(Entity $entity)
    {
        abort_unless(
            $entity->status === StatusEnum::active && $entity->type === EntityTypeEnum::post,
            404
        );

        if ($entity->isSocialPost()) {
            $url = $entity->socialRedirectUrl();
            abort_unless($url !== null, 404);

            return redirect()->away($url);
        }

        $related = Entity::query()
            ->where('status', StatusEnum::active)
            ->where('type', EntityTypeEnum::post)
            ->where('id', '!=', $entity->id)
            ->with('media')
            ->orderBy('order')
            ->take(3)
            ->get();

        return view('blog.show', compact('entity', 'related'));
    }

    /**
     * @param  Collection<int, Entity>  $posts
     * @return array{all: int, tiktok: int, tips: int}
     */
    private function channelCounts(Collection $posts): array
    {
        return [
            'all' => $posts->count(),
            'tiktok' => $posts->filter(fn (Entity $post) => $post->socialPlatform() === 'TikTok')->count(),
            'tips' => $posts->filter(fn (Entity $post) => $post->isHostedPost())->count(),
        ];
    }
}
