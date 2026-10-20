<?php

namespace App\Policies;

use App\Policies\Concerns\HasCrudPermissions;

class ProductPolicy
{
    use HasCrudPermissions;

    protected string $permissionPrefix = 'products';
}
