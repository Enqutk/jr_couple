<?php

namespace Database\Seeders;

use App\Models\ContentBlock;
use App\Models\PageSection;
use App\Models\User;
use Illuminate\Database\Seeder;

class StatsSeeder extends Seeder
{
    public function run(): void
    {
        $userId = User::query()->value('id');
        $sectionId = PageSection::query()->value('id');

        if (! $sectionId) {
            return;
        }

        ContentBlock::query()->updateOrCreate(
            ['slug' => 'stats'],
            [
                'section_id' => $sectionId,
                'type' => 'list',
                'title' => 'JR at a glance',
                'subtitle' => 'By the numbers',
                'list_items' => [
                    ['label' => 'JR brands', 'value' => 4, 'suffix' => ''],
                    ['label' => 'Store categories', 'value' => 4, 'suffix' => ''],
                    ['label' => 'Years serving clients', 'value' => 5, 'suffix' => '+'],
                    ['label' => 'Happy customers', 'value' => 500, 'suffix' => '+'],
                ],
                'display_order' => 8,
                'is_active' => true,
                'created_by' => $userId,
                'updated_by' => $userId,
            ]
        );
    }
}
