<?php

namespace App\Http\Controllers;

use App\Enums\EntityTypeEnum;
use App\Enums\StatusEnum;
use App\Models\Entity;
use App\Models\Team;

class AboutController extends Controller
{
    public function index()
    {
        $team = Team::where('status', StatusEnum::active)
            ->with('media')
            ->orderBy('order')
            ->get();

        $clients = Entity::where('status', StatusEnum::active)
            ->whereIn('type', [EntityTypeEnum::client, EntityTypeEnum::partner])
            ->with('media')
            ->orderBy('order')
            ->get();

        return view('about', compact('team', 'clients'));
    }
}
