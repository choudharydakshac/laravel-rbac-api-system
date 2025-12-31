<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class RbacSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::factory()->admin()->create();
        $userRole  = Role::factory()->user()->create();

        $manageUsers = Permission::factory()->manageUsers()->create();

        $adminRole->permissions()->attach($manageUsers);

        User::factory()
            ->withRole('admin')
            ->create([
                'email' => 'admin@example.com',
            ]);
    }
}
