<?php

namespace App\Policies;

use App\Policies\Concerns\HasCrudPermissions;

class ServicePolicy
{
    use HasCrudPermissions;

    protected string $permissionPrefix = 'services';
}
