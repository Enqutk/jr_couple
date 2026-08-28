<?php

namespace Database\Seeders;

use App\Enums\StatusEnum;
use App\Models\Organization;
use App\Models\OrganizationContact;
use App\Models\SocialRef;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $payload = [
            'title' => 'JR Couple',
            'tagline' => 'Artificial grass, mobiles, real estate, and hair — one trusted family of brands.',
            'meta_description' => 'JR — JR Ketema, JR Mobile, JR Real Estate, and Ruties Hair. Shop and connect with us.',
            'po_box' => '',
            'address' => 'Bole Road, Addis Ababa, Ethiopia',
            'opening_hours' => [
                [
                    'days' => ['mon', 'tue', 'wed', 'thu', 'fri', 'sat'],
                    'from' => '09:00:00',
                    'to' => '18:00:00',
                ],
            ],
            'map_url' => '',
            'theme' => [
                'accent' => '#ea580c',
                'accent_dark' => '#c2410c',
                'ink' => '#1c1917',
                'muted' => '#78716c',
                'bg' => '#ffffff',
                'surface' => '#ffffff',
                'line' => '#e7e5e4',
                'dark' => '#1c1917',
            ],
            'payment' => [
                'currency' => 'ETB',
                'telebirr_number' => '0911234567',
                'telebirr_name' => 'JR Couple',
                'bank_name' => 'Commercial Bank of Ethiopia (CBE)',
                'bank_account' => '1000123456789',
                'bank_account_name' => 'JR Couple Trading',
                'payment_note' => 'Send the exact amount in ETB, then WhatsApp us your receipt and the product name to confirm your order.',
            ],
            'status' => 'active',
        ];

        $organization = Organization::query()->first();
        if ($organization) {
            $organization->update($payload);
        } else {
            Organization::query()->create($payload);
        }

        OrganizationContact::query()->delete();
        SocialRef::query()->delete();

        foreach ([
            ['type' => 'email', 'value' => 'hello@jr.example'],
            ['type' => 'phone', 'value' => '+251 911 234 567'],
            ['type' => 'phone', 'value' => '+251 911 876 543'],
        ] as $contact) {
            OrganizationContact::create(array_merge($contact, ['status' => StatusEnum::active]));
        }

        foreach ([
            ['title' => 'Facebook', 'icon_class' => 'fa-brands fa-facebook-f', 'link' => 'https://www.facebook.com/'],
            ['title' => 'Instagram', 'icon_class' => 'fa-brands fa-instagram', 'link' => 'https://www.instagram.com/'],
            ['title' => 'TikTok', 'icon_class' => 'fa-brands fa-tiktok', 'link' => 'https://www.tiktok.com/'],
        ] as $social) {
            SocialRef::create(array_merge($social, ['status' => StatusEnum::active]));
        }
    }
}
