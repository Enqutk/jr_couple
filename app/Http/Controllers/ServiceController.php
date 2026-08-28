<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Enums\StatusEnum;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::where('status', StatusEnum::active)
            ->orderBy('order')
            ->get();

        return view('services.index', compact('services'));
    }

    public function show(string $slug)
    {
        $service = Service::where('slug', $slug)
            ->where('status', StatusEnum::active)
            ->firstOrFail();

        $allServices = Service::activeOrdered()->get();

        return view('services.show', compact('service', 'allServices'));
    }
}
