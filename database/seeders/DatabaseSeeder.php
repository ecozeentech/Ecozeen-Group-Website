<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Idempotent seeding entry point. Every seeder below uses
 * `updateOrCreate`/`firstOrCreate` keyed on a stable natural key (slug,
 * name, or location), so running `php artisan db:seed` repeatedly never
 * duplicates data. The first administrator account is intentionally not
 * created here — see `php artisan ecozeen:create-admin` and
 * docs/DEPLOYMENT.md.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            CompanySeeder::class,
            TaxonomySeeder::class,
            ProductSeeder::class,
            ServiceSeeder::class,
            ProjectSeeder::class,
            NavigationSeeder::class,
            PageSeeder::class,
            SettingsSeeder::class,
            BlogPostSeeder::class,
            JobListingSeeder::class,
        ]);
    }
}
