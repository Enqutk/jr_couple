<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $entities = ['blog', 'service', 'team', 'hero', 'entity', 'page', 'menu', 'organization'];
        $actions = ['create', 'read', 'update', 'delete'];

        foreach ($entities as $entity) {
            foreach ($actions as $action) {
                Permission::findOrCreate("{$action}-{$entity}", 'web');
            }
        }

        $admin = Role::findOrCreate('admin', 'web');
        $moderator = Role::findOrCreate('moderator', 'web');
        $blogger = Role::findOrCreate('blogger', 'web');

        $admin->syncPermissions(Permission::all());

        $moderator->syncPermissions(
            Permission::query()
                ->whereIn('name', [
                    'read-service', 'update-service', 'create-service',
                    'read-team', 'update-team', 'create-team',
                    'read-hero', 'update-hero', 'create-hero',
                    'read-entity', 'update-entity', 'create-entity',
                    'read-page', 'update-page',
                    'read-organization', 'update-organization',
                    'read-menu', 'update-menu',
                ])
                ->get()
        );

        $blogger->syncPermissions(
            Permission::query()
                ->where(function ($query) {
                    $query->where('name', 'like', '%-blog')
                        ->orWhereIn('name', ['read-service', 'read-team', 'read-hero', 'read-entity']);
                })
                ->get()
        );
    }
}
