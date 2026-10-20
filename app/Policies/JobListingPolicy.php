<?php

namespace App\Policies;

use App\Policies\Concerns\HasCrudPermissions;

class JobListingPolicy
{
    use HasCrudPermissions;

    protected string $permissionPrefix = 'jobs';

    public function create($user): bool
    {
        return $user->can('jobs.manage');
    }

    public function update($user, $model): bool
    {
        return $user->can('jobs.manage');
    }

    public function delete($user, $model): bool
    {
        return $user->can('jobs.manage');
    }
}
