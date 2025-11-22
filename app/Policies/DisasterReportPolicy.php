<?php

namespace App\Policies;

use App\Models\DisasterReport;
use App\Models\User;

class DisasterReportPolicy
{
    /**
     * Determine if the user can view any disaster reports.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view disaster reports
        return true;
    }

    /**
     * Determine if the user can view the disaster report.
     */
    public function view(User $user, DisasterReport $disasterReport): bool
    {
        // All authenticated users can view individual reports
        return true;
    }

    /**
     * Determine if the user can create disaster reports.
     */
    public function create(User $user): bool
    {
        // Admin, data-entry, and yfis users can create reports
        return $user->hasAnyRole(['admin', 'data-entry', 'yfis']);
    }

    /**
     * Determine if the user can update the disaster report.
     */
    public function update(User $user, DisasterReport $disasterReport): bool
    {
        // Admin and data-entry can update any report
        if ($user->hasAnyRole(['admin', 'data-entry'])) {
            return true;
        }

        // YFIS users can only update reports from their own affiliation
        if ($user->role === 'yfis' && $user->affiliation_id) {
            return $disasterReport->affiliation_id === $user->affiliation_id;
        }

        return false;
    }

    /**
     * Determine if the user can delete the disaster report.
     */
    public function delete(User $user, DisasterReport $disasterReport): bool
    {
        // Admin and data-entry can delete any report
        if ($user->hasAnyRole(['admin', 'data-entry'])) {
            return true;
        }

        // YFIS users can only delete reports from their own affiliation
        if ($user->role === 'yfis' && $user->affiliation_id) {
            return $disasterReport->affiliation_id === $user->affiliation_id;
        }

        return false;
    }

    /**
     * Determine if the user can publish the disaster report.
     */
    public function publish(User $user): bool
    {
        // Only admin can publish
        return $user->role === 'admin';
    }

    /**
     * Determine if the user can unpublish the disaster report.
     */
    public function unpublish(User $user): bool
    {
        // Only admin can unpublish
        return $user->role === 'admin';
    }
}
