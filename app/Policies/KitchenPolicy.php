<?php

namespace App\Policies;

use App\Models\Kitchen;
use App\Models\User;

class KitchenPolicy
{
    /**
     * Determine if the user can view any kitchens.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine if the user can view the kitchen.
     */
    public function view(User $user, Kitchen $kitchen): bool
    {
        return true;
    }

    /**
     * Determine if the user can create kitchens.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'data-entry', 'yfis']);
    }

    /**
     * Determine if the user can update the kitchen.
     */
    public function update(User $user, Kitchen $kitchen): bool
    {
        // Admin and data-entry can update any kitchen
        if ($user->hasAnyRole(['admin', 'data-entry'])) {
            return true;
        }

        // YFIS users can only update kitchens from their own affiliation
        if ($user->role === 'yfis' && $user->affiliation_id) {
            return $kitchen->affiliation_id === $user->affiliation_id;
        }

        return false;
    }

    /**
     * Determine if the user can delete the kitchen.
     */
    public function delete(User $user, Kitchen $kitchen): bool
    {
        // Admin and data-entry can delete any kitchen
        if ($user->hasAnyRole(['admin', 'data-entry'])) {
            return true;
        }

        // YFIS users can only delete kitchens from their own affiliation
        if ($user->role === 'yfis' && $user->affiliation_id) {
            return $kitchen->affiliation_id === $user->affiliation_id;
        }

        return false;
    }
}
