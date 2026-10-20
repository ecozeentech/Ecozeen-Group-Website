<?php

namespace App\Policies;

use App\Policies\Concerns\HasCrudPermissions;

class ProjectPolicy
{
    use HasCrudPermissions;

    protected string $permissionPrefix = 'projects';
}
