<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('users.manage');
    }

    public function view(User $user, User $target): bool
    {
        return $user->can('users.manage') || $user->id === $target->id;
    }

    public function create(User $user): bool
    {
        return $user->can('users.manage');
    }

    public function update(User $user, User $target): bool
    {
        return $user->can('users.manage') || $user->id === $target->id;
    }

    /**
     * Only Super Administrators may change role assignments, and nobody
     * may grant themselves additional roles, preventing self-escalation.
     */
    public function manageRoles(User $user, User $target): bool
    {
        if ($user->id === $target->id) {
            return false;
        }

        return $user->hasRole('Super Administrator');
    }

    public function delete(User $user, User $target): bool
    {
        if ($user->id === $target->id) {
            return false;
        }

        if ($target->hasRole('Super Administrator') && ! $user->hasRole('Super Administrator')) {
            return false;
        }

        return $user->can('users.manage');
    }
}
