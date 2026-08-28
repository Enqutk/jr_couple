<?php

namespace App\Models;

use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialRef extends Model 
{
    use SoftDeletes;
    protected $table = 'social_refs';

    /**
    * Mass assignable attributes.
    */
    protected $fillable = [
        'title',
        'link',
        'description',
        'icon_class',
        'order',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
    * Attribute casting.
    */
    protected $casts = [
        'status'      => StatusEnum::class,
        'order'       => 'integer',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
        'deleted_at'  => 'datetime',
    ];

    /**
    * Creator relationship.
    */

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
    * Updater relationship.
    */

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
