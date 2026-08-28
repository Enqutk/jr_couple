<?php

namespace App\Http\Controllers;

use App\Enums\EntityTypeEnum;
use App\Enums\StatusEnum;
use App\Models\Entity;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Entity::query()
            ->where('status', StatusEnum::active)
            ->where('type', EntityTypeEnum::post)
            ->with('media')
            ->orderBy('order')
            ->get();

        return view('blog.index', compact('posts'));
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
}
