<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class CreateAdministratorCommand extends Command
{
    protected $signature = 'ecozeen:create-admin
        {--name= : Full name of the administrator}
        {--email= : Login email address}
        {--password= : Login password (omit to be prompted securely)}
        {--role=Super Administrator : Role to assign}';

    protected $description = 'Interactively create (or promote) an administrator account without exposing credentials in configuration files.';

    public function handle(): int
    {
        $name = $this->option('name') ?: $this->ask('Administrator name');
        $email = $this->option('email') ?: $this->ask('Administrator email');
        $password = $this->option('password') ?: $this->secret('Administrator password (min 12 characters)');
        $role = $this->option('role');

        $validator = Validator::make(
            ['name' => $name, 'email' => $email, 'password' => $password, 'role' => $role],
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
                'password' => ['required', 'string', 'min:12'],
                'role' => ['required', 'string', 'exists:roles,name'],
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        if (! Role::query()->where('name', $role)->exists()) {
            $this->error("Role [{$role}] does not exist. Run the RolesAndPermissionsSeeder first.");

            return self::FAILURE;
        }

        $user = User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );

        $user->syncRoles([$role]);

        $this->info("Administrator account ready: {$user->email} ({$role}).");

        return self::SUCCESS;
    }
}
