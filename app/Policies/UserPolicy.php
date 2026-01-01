<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function viewAny(User $authUser): bool
    {
        return $authUser->hasPermission('view_users');
    }

    public function create(User $authUser): bool
    {
        return $authUser->hasPermission('create_users');
    }

    public function update(User $authUser): bool
    {
        return $authUser->hasPermission('edit_users');
    }

    public function delete(User $authUser): bool
    {
        return $authUser->hasPermission('delete_users');
    }
}
