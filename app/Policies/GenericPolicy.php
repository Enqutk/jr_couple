<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Str;

class GenericPolicy
{
    use HandlesAuthorization;

      public function view(User $user, object $model)
    {
        return $user->hasPermissionTo('read-'  . Str::kebab(class_basename($model)));
    }

    public function create(User $user, object $model)
    {
        return $user->hasPermissionTo('create-' . Str::kebab(class_basename($model)));
    }
    public function edit(User $user, object $model)
    {
        return $user->hasPermissionTo('update-' . Str::kebab(class_basename($model)));
    }
    public function update(User $user, object $model)
    {
        return $user->hasPermissionTo('update-' . Str::kebab(class_basename($model)));
    }

    public function delete(User $user, object $model)
    {
        return $user->hasPermissionTo('delete-' . Str::kebab(class_basename($model)));
    }
}