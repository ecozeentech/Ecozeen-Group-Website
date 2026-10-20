<?php

namespace App\Policies;

use App\Models\Page;
use App\Models\User;

class PagePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('pages.view');
    }

    public function view(User $user, Page $page): bool
    {
        return $user->can('pages.view');
    }

    public function create(User $user): bool
    {
        return $user->can('pages.create');
    }

    public function update(User $user, Page $page): bool
    {
        return $user->can('pages.update');
    }

    public function publish(User $user, Page $page): bool
    {
        return $user->can('pages.publish');
    }

    public function delete(User $user, Page $page): bool
    {
        if ($page->is_homepage) {
            return false;
        }

        return $user->can('pages.delete');
    }

    public function restore(User $user, Page $page): bool
    {
        return $user->can('pages.delete');
    }

    public function forceDelete(User $user, Page $page): bool
    {
        return $user->hasRole('Super Administrator');
    }
}
