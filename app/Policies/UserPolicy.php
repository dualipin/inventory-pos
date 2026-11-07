<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return $user->isAdmin();
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
    public function update(User $user, User $model): bool
    {
        if (! $user->isAdmin()) {
            return false;
        }

        // Impide explícitamente que un administrador cambie su propio rol
        if ($model->id === $user->id && $model->isDirty('role') && $model->getOriginal('role') !== $model->role) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can change the role of the model.
     */
    public function changeRole(User $user, User $model): bool
    {
        return $user->isAdmin() && $model->id !== $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->isAdmin() && $model->id !== $user->id;
    }

    /**
     * Determine whether the user can bulk delete models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->isAdmin() && $model->id !== $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->isAdmin() && $model->id !== $user->id;
    }
}
