<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use App\Traits\HasUserStamps;


class Team extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia , HasUserStamps;

    protected $fillable = [
        'first_name',
        'last_name',
        'title',
        'description',
        'image',
        'status',
        'founder',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
        'founder' => 'boolean',
        'status' => \App\Enums\StatusEnum::class,
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }


    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('team-images')

            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'])
            ->registerMediaConversions(function () {
                $this->addMediaConversion('thumb')
                    ->width(150)
                    ->height(150)
                    ->sharpen(10);
            });
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . ($this->last_name ?? ''));
    }

    public function getImageUrlAttribute(): ?string
    {
        $url = $this->getFirstMediaUrl('team-images');

        return $url !== '' ? $url : null;
    }
}
