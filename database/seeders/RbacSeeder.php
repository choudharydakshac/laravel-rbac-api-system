<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RbacSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Roles
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            ['label' => 'Administrator']
        );

        $userRole  = Role::firstOrCreate(
            ['name' => 'user'],
            ['label' => 'User']
        );

        // Permissions
        $permissions = [
            'view_users'   => 'View users',
            'create_users' => 'Create users',
            'edit_users'   => 'Edit users',
            'delete_users' => 'Delete users',
        ];

        $permissionModels = collect($permissions)->map(
            fn ($label, $name) =>
                Permission::firstOrCreate(
                    ['name' => $name],
                    ['label' => $label]
                )
        );

        // Attach permissions
        $adminRole->permissions()->sync(
            $permissionModels->pluck('id')->toArray()
        );

        $userRole->permissions()->sync(
            Permission::where('name', 'view_users')->pluck('id')->toArray()
        );

        // Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ]
        );

        $admin->roles()->sync([$adminRole->id]);

        /* $adminRole = Role::factory()->admin()->create();
        $userRole  = Role::factory()->user()->create();

        $manageUsers = Permission::factory()->manageUsers()->create();

        $adminRole->permissions()->attach($manageUsers);

        User::factory()
            ->withRole('admin')
            ->create([
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
            ]); */
    }
}
