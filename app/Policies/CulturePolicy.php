<?php

namespace App\Policies;

use App\Models\Culture;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CulturePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Tous les utilisateurs authentifiés peuvent voir le catalogue
        return $user->isAgriculteur() || $user->isAdmin() || $user->isTechnicien();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Culture $culture): bool
    {
        return $user->isAgriculteur() || $user->isAdmin() || $user->isTechnicien();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Culture $culture): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Culture $culture): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Culture $culture): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Culture $culture): bool
    {
        return $user->isAdmin();
    }
}
