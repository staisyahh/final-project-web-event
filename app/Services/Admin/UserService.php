<?php

namespace App\Services\Admin;

use App\Models\User;

class UserService
{
    /**
     * Change the role of a user.
     *
     * @param int $userId
     * @param string $newRole
     * @return User
     */
    public function changeRole(int $userId, string $newRole): User
    {
        $user = User::findOrFail($userId);

        // Basic validation to prevent arbitrary roles
        $validRoles = ['admin', 'peserta'];
        if (!in_array($newRole, $validRoles)) {
            throw new \InvalidArgumentException("Invalid role provided.");
        }

        // Prevent changing the role of the first user (super admin)
        if ($user->id === 1) {
            throw new \Exception("Cannot change the role of the super admin.");
        }

        $user->role = $newRole;
        $user->save();

        return $user;
    }
}
