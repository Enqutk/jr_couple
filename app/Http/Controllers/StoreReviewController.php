<?php

namespace App\Http\Controllers;

use App\Enums\EntityTypeEnum;
use App\Enums\StatusEnum;
use App\Models\Entity;
use App\Models\ProductReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StoreReviewController extends Controller
{
    public function store(Request $request, Entity $entity): RedirectResponse
    {
        abort_unless(
            $entity->status === StatusEnum::active && $entity->type === EntityTypeEnum::product,
            404
        );

        $validated = $request->validate([
            'author_name' => ['required', 'string', 'max:120'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'body' => ['required', 'string', 'min:10', 'max:2000'],
        ]);

        ProductReview::query()->create([
            'entity_id' => $entity->id,
            'author_name' => $validated['author_name'],
            'rating' => $validated['rating'],
            'body' => $validated['body'],
            'status' => StatusEnum::active,
        ]);

        return redirect()
            ->route('store.show', $entity)
            ->with('review_success', 'Thanks! Your review has been posted.')
            ->withFragment('reviews');
    }
}
