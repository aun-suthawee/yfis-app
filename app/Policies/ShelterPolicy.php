<?php

namespace App\Policies;

use App\Models\Shelter;
use App\Models\User;

class ShelterPolicy
{
    /**
     * Determine if the user can view any shelters.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine if the user can view the shelter.
     */
    public function view(User $user, Shelter $shelter): bool
    {
        return true;
    }

    /**
     * Determine if the user can create shelters.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'data-entry', 'yfis']);
    }

    /**
     * Determine if the user can update the shelter.
     */
    public function update(User $user, Shelter $shelter): bool
    {
        // Admin and data-entry can update any shelter
        if ($user->hasAnyRole(['admin', 'data-entry'])) {
            return true;
        }

        // YFIS users can only update shelters from their own affiliation
        if ($user->role === 'yfis' && $user->affiliation_id) {
            return $shelter->affiliation_id === $user->affiliation_id;
        }

        return false;
    }

    /**
     * Determine if the user can delete the shelter.
     */
    public function delete(User $user, Shelter $shelter): bool
    {
        // Admin and data-entry can delete any shelter
        if ($user->hasAnyRole(['admin', 'data-entry'])) {
            return true;
        }

        // YFIS users can only delete shelters from their own affiliation
        if ($user->role === 'yfis' && $user->affiliation_id) {
            return $shelter->affiliation_id === $user->affiliation_id;
        }

        return false;
    }
}
