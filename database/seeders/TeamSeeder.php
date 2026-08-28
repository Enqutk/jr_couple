<?php

namespace Database\Seeders;

use App\Enums\StatusEnum;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        $userId = User::query()->value('id');
        $base = public_path('assets/images/majiworks');

        $members = [
            [
                'first_name' => 'Wanjiku',
                'last_name' => 'Mwangi',
                'title' => 'Managing Director · Hydrogeologist',
                'description' => 'Leads JR Couple strategy across the family of brands.',
                'founder' => true,
                'order' => 1,
                'photo' => $base.'/maji-team-amina.png',
            ],
            [
                'first_name' => 'Joseph',
                'last_name' => 'Otieno',
                'title' => 'Lead Irrigation Agronomist',
                'description' => 'Turns crop water demand into buildable drip, sprinkler, and canal layouts for cooperatives and estates.',
                'founder' => false,
                'order' => 2,
                'photo' => $base.'/maji-team-joseph.png',
            ],
            [
                'first_name' => 'Mercy',
                'last_name' => 'Chebet',
                'title' => 'GIS & Hydrology Lead',
                'description' => 'Builds basin inventories, flood maps, and field survey kits used by counties and NGO partners.',
                'founder' => false,
                'order' => 3,
                'photo' => $base.'/maji-team-mercy.png',
            ],
            [
                'first_name' => 'Kwame',
                'last_name' => 'Asante',
                'title' => 'WASH Governance Advisor',
                'description' => 'Facilitates water committees, tariff models, and post-construction coaching so schemes keep running.',
                'founder' => false,
                'order' => 4,
                'photo' => $base.'/maji-team-kwame.png',
            ],
        ];

        foreach ($members as $member) {
            $photo = $member['photo'];
            unset($member['photo']);

            $team = Team::query()->updateOrCreate(
                [
                    'first_name' => $member['first_name'],
                    'last_name' => $member['last_name'],
                ],
                [
                    ...$member,
                    'status' => StatusEnum::active,
                    'created_by' => $userId,
                    'updated_by' => $userId,
                ]
            );

            if (is_file($photo)) {
                $team->clearMediaCollection('team-images');
                $team->addMedia($photo)
                    ->preservingOriginal()
                    ->toMediaCollection('team-images');
            }
        }
    }
}
