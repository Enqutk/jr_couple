<?php

namespace App\Http\Controllers;

use App\Enums\EntityTypeEnum;
use App\Enums\StatusEnum;
use App\Models\Entity;
use App\Models\Service;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $services = Service::activeOrdered()->get();
        $category = $request->query('category');

        $productsQuery = Entity::query()
            ->where('status', StatusEnum::active)
            ->where('type', EntityTypeEnum::product)
            ->with('media')
            ->orderBy('order');

        if ($category) {
            $productsQuery->where('category', $category);
        }

        $products = $productsQuery->get();
        $activeService = $category
            ? $services->firstWhere('slug', $category)
            : null;

        return view('store.index', compact('services', 'products', 'category', 'activeService'));
    }
}
