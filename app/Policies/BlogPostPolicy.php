<?php

namespace App\Policies;

use App\Models\BlogPost;
use App\Models\User;

class BlogPostPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('blog.view');
    }

    public function view(User $user, BlogPost $post): bool
    {
        return $user->can('blog.view');
    }

    public function create(User $user): bool
    {
        return $user->can('blog.create');
    }

    /**
     * Authors (without a broader editorial role) may only edit their own
     * drafts. Editors, Content Managers, Administrators, and Super
     * Administrators may edit any post.
     */
    public function update(User $user, BlogPost $post): bool
    {
        if (! $user->can('blog.update')) {
            return false;
        }

        if ($this->isAuthorScopedOnly($user)) {
            return $post->author_id === $user->id;
        }

        return true;
    }

    public function publish(User $user, BlogPost $post): bool
    {
        return $user->can('blog.publish');
    }

    public function delete(User $user, BlogPost $post): bool
    {
        return $user->can('blog.delete');
    }

    public function restore(User $user, BlogPost $post): bool
    {
        return $user->can('blog.delete');
    }

    public function forceDelete(User $user, BlogPost $post): bool
    {
        return $user->hasRole('Super Administrator');
    }

    private function isAuthorScopedOnly(User $user): bool
    {
        return $user->hasRole('Author')
            && ! $user->hasAnyRole(['Super Administrator', 'Administrator', 'Content Manager', 'Editor']);
    }
}
