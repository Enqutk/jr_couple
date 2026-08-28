<?php

namespace Database\Seeders;

use App\Enums\StatusEnum;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        // Replace prior demo services with JR brands
        Service::query()->forceDelete();

        $services = [
            [
                'slug' => 'jr-ketema',
                'title' => 'JR Ketema',
                'short_description' => 'Premium artificial grass for homes, sports fields, and commercial spaces.',
                'quote' => 'Green that stays green — all year',
                'description' => 'JR Ketema supplies and installs artificial grass for residential lawns, playgrounds, rooftops, and sports pitches. We help you pick the right pile height, density, and underlay for Ethiopian sun and dust — then deliver a finish that looks sharp with less water and maintenance.',
                'features' => '<ul><li>Residential &amp; commercial turf</li><li>Sports &amp; play surfaces</li><li>Supply + installation</li><li>UV-stable fibres</li><li>Aftercare guidance</li></ul>',
                'order' => 1,
                'status' => StatusEnum::active,
            ],
            [
                'slug' => 'jr-mobile',
                'title' => 'JR Mobile',
                'short_description' => 'New and trusted phones, accessories, and everyday mobile deals.',
                'quote' => 'Phones you can trust — prices you can feel',
                'description' => 'JR Mobile is our retail arm for smartphones, tablets, and accessories. From flagship devices to everyday handsets, we focus on clear pricing, warranty support, and friendly in-store help — the same approach you expect from a neighbourhood tech shop.',
                'features' => '<ul><li>Smartphones &amp; feature phones</li><li>Accessories &amp; chargers</li><li>Warranty support</li><li>Trade-in friendly advice</li><li>In-store pickup</li></ul>',
                'order' => 2,
                'status' => StatusEnum::active,
            ],
            [
                'slug' => 'jr-real-estate',
                'title' => 'JR Real Estate',
                'short_description' => 'Homes, plots, and commercial spaces — buying, selling, and renting with clarity.',
                'quote' => 'Property moves made simple',
                'description' => 'JR Real Estate connects buyers, sellers, and renters with verified listings and practical guidance. Whether you are looking for a family home, an investment plot, or a shop space, our agents walk you through viewings, paperwork, and negotiation.',
                'features' => '<ul><li>Residential sales &amp; rentals</li><li>Land &amp; commercial listings</li><li>Viewings &amp; negotiation</li><li>Documentation support</li><li>Local market insight</li></ul>',
                'order' => 3,
                'status' => StatusEnum::active,
            ],
            [
                'slug' => 'ruties-hair',
                'title' => 'Ruties Hair',
                'short_description' => 'Hair care, styling products, and beauty essentials for everyday glow.',
                'quote' => 'Hair that turns heads',
                'description' => 'Ruties Hair is our beauty line for wigs, extensions, hair care, and styling tools. Whether you want a natural look or a bold new style, we help you choose quality pieces and products you can trust — with friendly in-store advice.',
                'features' => '<ul><li>Wigs &amp; hair extensions</li><li>Shampoo, oil &amp; treatments</li><li>Styling tools &amp; accessories</li><li>In-store fitting advice</li><li>Everyday &amp; occasion looks</li></ul>',
                'order' => 4,
                'status' => StatusEnum::active,
            ],
        ];

        foreach ($services as $serviceData) {
            Service::query()->create($serviceData);
        }
    }
}
