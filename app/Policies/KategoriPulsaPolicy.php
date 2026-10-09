<?php

namespace App\Policies;

use App\Models\KategoriPulsa;
use App\Models\User;

class KategoriPulsaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'Admin';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, KategoriPulsa $kategoriPulsa): bool
    {
        return $user->role === 'Admin';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'Admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, KategoriPulsa $kategoriPulsa): bool
    {
        return $user->role === 'Admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, KategoriPulsa $kategoriPulsa): bool
    {
        return $user->role === 'Admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, KategoriPulsa $kategoriPulsa): bool
    {
        return $user->role === 'Admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, KategoriPulsa $kategoriPulsa): bool
    {
        return $user->role === 'Admin';
    }
}
