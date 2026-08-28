<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class MenuItem extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'menu_id',
        'parent_id',
        'title',
        'icon',
        'link_type',
        'url',
        'target',
        'order_number'
    ];

    public function menu()
    {
        return $this->belongsTo(MenuLocation::class , 'menu_id');
    }

    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order_number');
    }
}
