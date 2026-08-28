<?php

namespace App\Http\Controllers;

use App\Enums\EntityTypeEnum;
use App\Enums\StatusEnum;
use App\Models\Entity;
use App\Models\Hero;
use App\Models\Service;

class HomeController extends Controller
{
    public function index()
    {
        $heroes = Hero::where('status', StatusEnum::active)
            ->with('media')
            ->orderBy('order')
            ->get();

        $services = Service::activeOrdered()->get();

        $storeProducts = Entity::where('status', StatusEnum::active)
            ->where('type', EntityTypeEnum::product)
            ->with('media')
            ->orderBy('order')
            ->take(6)
            ->get();

        $posts = Entity::where('status', StatusEnum::active)
            ->where('type', EntityTypeEnum::post)
            ->with('media')
            ->orderBy('order')
            ->take(3)
            ->get();

        return view('index', compact('heroes', 'services', 'storeProducts', 'posts'));
    }
}
