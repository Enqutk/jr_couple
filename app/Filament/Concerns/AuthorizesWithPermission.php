<?php

namespace App\Filament\Concerns;

use Illuminate\Support\Facades\Auth;

trait AuthorizesWithPermission
{
    /**
     * Permission entity key, e.g. service, hero, page, menu, organization.
     * Override on the resource when the model name does not match.
     */
    protected static function permissionKey(): string
    {
        return static::$permissionKey ?? 'page';
    }

    public static function canViewAny(): bool
    {
        return static::userCan('read');
    }

    public static function canCreate(): bool
    {
        return static::userCan('create');
    }

    public static function canEdit($record): bool
    {
        return static::userCan('update');
    }

    public static function canDelete($record): bool
    {
        return static::userCan('delete');
    }

    public static function canDeleteAny(): bool
    {
        return static::userCan('delete');
    }

    public static function canForceDelete($record): bool
    {
        return static::userCan('delete');
    }

    public static function canForceDeleteAny(): bool
    {
        return static::userCan('delete');
    }

    public static function canRestore($record): bool
    {
        return static::userCan('update');
    }

    public static function canRestoreAny(): bool
    {
        return static::userCan('update');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canViewAny();
    }

    protected static function userCan(string $action): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->can("{$action}-" . static::permissionKey());
    }
}
