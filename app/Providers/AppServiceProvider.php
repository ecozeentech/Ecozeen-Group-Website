<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Super Administrators always pass authorization checks, even for
        // permissions introduced after their account was provisioned. All
        // other roles are evaluated strictly against assigned permissions.
        //
        // The self-protection rules in UserPolicy (no self-deletion, no
        // self-role-escalation) must still apply to Super Administrators,
        // so those abilities are deferred to the policy instead of being
        // blanket-approved here.
        Gate::before(function (User $user, string $ability, array $arguments = []) {
            if (! $user->hasRole('Super Administrator')) {
                return null;
            }

            $target = $arguments[0] ?? null;

            if (in_array($ability, ['delete', 'manageRoles'], true) && $target instanceof User && $target->is($user)) {
                return null;
            }

            return true;
        });

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        $this->ensurePublicMediaDirectory();
    }

    /**
     * Uploads are stored in public/media so they can be served without a
     * symlink. Create the folder on boot if the deploy omitted it.
     */
    private function ensurePublicMediaDirectory(): void
    {
        $directory = public_path('media');

        if (! is_dir($directory)) {
            @mkdir($directory, 0755, true);
        }
    }
}
