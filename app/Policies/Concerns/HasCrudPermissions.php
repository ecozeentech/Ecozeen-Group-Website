<?php

namespace App\Policies\Concerns;

use App\Models\User;

/**
 * Shared server-side CRUD authorization for simple content-type policies.
 * Concrete policies set $permissionPrefix (e.g. "companies") and this trait
 * maps standard policy methods to the matching permission (companies.view,
 * companies.create, companies.update, companies.delete).
 */
trait HasCrudPermissions
{
    protected string $permissionPrefix;

    public function viewAny(User $user): bool
    {
        return $user->can("{$this->permissionPrefix}.view");
    }

    public function view(User $user, $model): bool
    {
        return $user->can("{$this->permissionPrefix}.view");
    }

    public function create(User $user): bool
    {
        return $user->can("{$this->permissionPrefix}.create");
    }

    public function update(User $user, $model): bool
    {
        return $user->can("{$this->permissionPrefix}.update");
    }

    public function delete(User $user, $model): bool
    {
        return $user->can("{$this->permissionPrefix}.delete");
    }

    public function restore(User $user, $model): bool
    {
        return $user->can("{$this->permissionPrefix}.delete");
    }

    public function forceDelete(User $user, $model): bool
    {
        return $user->hasRole('Super Administrator');
    }
}
