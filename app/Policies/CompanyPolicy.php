<?php

namespace App\Policies;

use App\Policies\Concerns\HasCrudPermissions;

class CompanyPolicy
{
    use HasCrudPermissions;

    protected string $permissionPrefix = 'companies';
}
