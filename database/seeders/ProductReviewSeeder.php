<?php

namespace Database\Seeders;

use App\Enums\EntityTypeEnum;
use App\Enums\StatusEnum;
use App\Models\Entity;
use App\Models\ProductReview;
use Illuminate\Database\Seeder;

class ProductReviewSeeder extends Seeder
{
    public function run(): void
    {
        ProductReview::query()->delete();

        $samples = [
            'Flagship Display Phone' => [
                ['author_name' => 'Meron T.', 'rating' => 5, 'body' => 'Great camera and battery. JR Mobile helped me set everything up in store.'],
                ['author_name' => 'Samuel K.', 'rating' => 4, 'body' => 'Premium feel and smooth display. Price was negotiable which helped.'],
            ],
            'Home Lawn Turf 30mm' => [
                ['author_name' => 'Hanna G.', 'rating' => 5, 'body' => 'Looks natural on our balcony. Installation team was quick and tidy.'],
            ],
            'Natural Wave Wig' => [
                ['author_name' => 'Ruth A.', 'rating' => 5, 'body' => 'Beautiful unit and the staff at Ruties Hair matched the colour perfectly.'],
                ['author_name' => 'Lydia M.', 'rating' => 4, 'body' => 'Soft hair and good value. Will come back for bundles.'],
            ],
        ];

        foreach ($samples as $productName => $reviews) {
            $product = Entity::query()
                ->where('type', EntityTypeEnum::product)
                ->where('name', $productName)
                ->first();

            if (! $product) {
                continue;
            }

            foreach ($reviews as $review) {
                ProductReview::query()->create([
                    ...$review,
                    'entity_id' => $product->id,
                    'status' => StatusEnum::active,
                ]);
            }
        }
    }
}
