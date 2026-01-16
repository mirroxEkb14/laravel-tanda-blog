<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndUsersSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (RoleEnum::cases() as $role) {
            Role::firstOrCreate(
                ['name' => $role->value, 'guard_name' => 'web']
            );
        }

        $legacySuperAdmin = Role::query()
            ->where('name', 'super admin')
            ->where('guard_name', 'web')
            ->first();
        $canonicalSuperAdmin = Role::query()
            ->where('name', RoleEnum::SuperAdmin->value)
            ->where('guard_name', 'web')
            ->first();

        if ($legacySuperAdmin && ! $canonicalSuperAdmin) {
            $legacySuperAdmin->update(['name' => RoleEnum::SuperAdmin->value]);
            $canonicalSuperAdmin = $legacySuperAdmin;
        } elseif ($legacySuperAdmin && $canonicalSuperAdmin) {
            $legacySuperAdmin->delete();
        }

        Artisan::call('shield:generate', [
            '--all' => true,
            '--panel' => 'admin',
            '--no-interaction' => true,
        ]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = Permission::query()->pluck('name');

        if ($permissions->isNotEmpty()) {
            Role::findByName(RoleEnum::SuperAdmin->value)->syncPermissions($permissions);

            $adminPermissions = Permission::query()
                ->whereNot(fn ($query) => $query
                    ->where('name', 'like', '%role%')
                    ->orWhere('name', 'like', '%permission%'))
                ->pluck('name');

            Role::findByName(RoleEnum::Admin->value)->syncPermissions($adminPermissions);
        }

        $users = [
            [
                'email' => 'admin@tandakids.kz',
                'name' => 'Admin',
                'password' => Hash::make('qwerty123456'),
                'role' => RoleEnum::Admin->value,
            ],
            [
                'email' => 'superadmin@tandakids.kz',
                'name' => 'Super Admin',
                'password' => Hash::make('qwerty123456'),
                'role' => RoleEnum::SuperAdmin->value,
            ],
            [
                'email' => 'queequeg@gmail.com',
                'name' => 'Dana Scully',
                'password' => Hash::make('app'),
                'role' => RoleEnum::User->value,
            ],
            [
                'email' => 'trust_no1@gmail.com',
                'name' => 'Fox Mulder',
                'password' => Hash::make('app'),
                'role' => RoleEnum::User->value,
            ],
            [
                'email' => 'blackmesa@gmail.com',
                'name' => 'Gordon Freeman',
                'password' => Hash::make('app'),
                'role' => RoleEnum::User->value,
            ],
            [
                'email' => 'ppth@gmail.com',
                'name' => 'Gregory House',
                'password' => Hash::make('app'),
                'role' => RoleEnum::User->value,
            ],
            [
                'email' => 'glados@gmail.com',
                'name' => 'Caroline',
                'password' => Hash::make('app'),
                'role' => RoleEnum::User->value,
            ],
            [
                'email' => 'nostromo@gmail.com',
                'name' => 'Ellen Ripley',
                'password' => Hash::make('app'),
                'role' => RoleEnum::User->value,
            ],
        ];

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $userData['password'],
                    'email_verified_at' => now(),
                ]
            );

            $user->syncRoles([$userData['role']]);
        }
    }
}
