<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * The complete, authoritative list of permissions enforced server-side
     * throughout the admin dashboard. Every protected admin route and
     * Policy method references one of these identifiers.
     */
    public const PERMISSIONS = [
        'pages.view', 'pages.create', 'pages.update', 'pages.publish', 'pages.delete',
        'companies.view', 'companies.create', 'companies.update', 'companies.delete',
        'products.view', 'products.create', 'products.update', 'products.delete',
        'services.view', 'services.create', 'services.update', 'services.delete',
        'projects.view', 'projects.create', 'projects.update', 'projects.delete',
        'blog.view', 'blog.create', 'blog.update', 'blog.publish', 'blog.delete',
        'media.view', 'media.upload', 'media.delete',
        'navigation.manage',
        'inquiries.view', 'inquiries.manage',
        'newsletter.view', 'newsletter.manage',
        'jobs.view', 'jobs.manage',
        'redirects.manage',
        'settings.manage',
        'theme.manage',
        'users.manage',
        'roles.manage',
        'activity.view',
    ];

    public const ROLE_PERMISSIONS = [
        'Super Administrator' => ['*'],
        'Administrator' => [
            'pages.view', 'pages.create', 'pages.update', 'pages.publish', 'pages.delete',
            'companies.view', 'companies.create', 'companies.update', 'companies.delete',
            'products.view', 'products.create', 'products.update', 'products.delete',
            'services.view', 'services.create', 'services.update', 'services.delete',
            'projects.view', 'projects.create', 'projects.update', 'projects.delete',
            'blog.view', 'blog.create', 'blog.update', 'blog.publish', 'blog.delete',
            'media.view', 'media.upload', 'media.delete',
            'navigation.manage',
            'inquiries.view', 'inquiries.manage',
            'newsletter.view', 'newsletter.manage',
            'jobs.view', 'jobs.manage',
            'redirects.manage',
            'settings.manage',
            'theme.manage',
            'users.manage',
            'activity.view',
        ],
        'Content Manager' => [
            'pages.view', 'pages.create', 'pages.update', 'pages.publish',
            'companies.view', 'companies.create', 'companies.update',
            'products.view', 'products.create', 'products.update',
            'services.view', 'services.create', 'services.update',
            'projects.view', 'projects.create', 'projects.update',
            'blog.view', 'blog.create', 'blog.update', 'blog.publish',
            'media.view', 'media.upload',
            'navigation.manage',
        ],
        'Editor' => [
            'pages.view', 'pages.create', 'pages.update',
            'companies.view', 'companies.update',
            'products.view', 'products.update',
            'services.view', 'services.update',
            'projects.view', 'projects.update',
            'blog.view', 'blog.create', 'blog.update',
            'media.view', 'media.upload',
        ],
        'Author' => [
            'blog.view', 'blog.create', 'blog.update',
            'media.view', 'media.upload',
        ],
        'SEO Manager' => [
            'pages.view', 'pages.update',
            'companies.view', 'companies.update',
            'products.view', 'products.update',
            'services.view', 'services.update',
            'projects.view', 'projects.update',
            'blog.view', 'blog.update',
            'redirects.manage',
            'media.view',
        ],
        'Inquiry Manager' => [
            'inquiries.view', 'inquiries.manage',
            'newsletter.view', 'newsletter.manage',
            'jobs.view', 'jobs.manage',
        ],
        'Read-only Analyst' => [
            'pages.view', 'companies.view', 'products.view', 'services.view',
            'projects.view', 'blog.view', 'media.view', 'inquiries.view',
            'newsletter.view', 'jobs.view', 'activity.view',
        ],
    ];

    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        foreach (self::PERMISSIONS as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        foreach (self::ROLE_PERMISSIONS as $roleName => $permissions) {
            $role = Role::findOrCreate($roleName, 'web');

            if ($permissions === ['*']) {
                $role->syncPermissions(Permission::all());
            } else {
                $role->syncPermissions($permissions);
            }
        }

        // The first administrator account is created separately via the
        // `php artisan ecozeen:create-admin` command so that credentials
        // are never stored in environment files or seeders. See
        // docs/DEPLOYMENT.md for the documented bootstrap procedure.
    }
}
