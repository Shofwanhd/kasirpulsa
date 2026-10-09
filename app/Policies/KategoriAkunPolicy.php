<?php

namespace App\Policies;

use App\Models\KategoriAkun;
use App\Models\User;

class KategoriAkunPolicy
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
    public function view(User $user, KategoriAkun $kategoriAkun): bool
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
    public function update(User $user, KategoriAkun $kategoriAkun): bool
    {
        return $user->role === 'Admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, KategoriAkun $kategoriAkun): bool
    {
        return $user->role === 'Admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, KategoriAkun $kategoriAkun): bool
    {
        return $user->role === 'Admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, KategoriAkun $kategoriAkun): bool
    {
        return $user->role === 'Admin';
    }
}
