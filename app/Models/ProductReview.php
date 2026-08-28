<?php

namespace App\Models;

use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductReview extends Model
{
    protected $fillable = [
        'entity_id',
        'author_name',
        'rating',
        'body',
        'status',
    ];

    protected $casts = [
        'rating' => 'integer',
        'status' => StatusEnum::class,
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Entity::class, 'entity_id');
    }
}
