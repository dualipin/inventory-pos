<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Sale;
use App\Models\User;

class SalePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(UserRole::ADMIN, UserRole::CASHIER);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Sale $sale): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isCashier() && $sale->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(UserRole::ADMIN, UserRole::CASHIER);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Sale $sale): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Sale $sale): bool
    {
        return false;
    }

    /**
     * Determine whether the user can bulk delete models.
     */
    public function deleteAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Sale $sale): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Sale $sale): bool
    {
        return false;
    }

    /**
     * Determine whether the user can cancel the sale.
     */
    public function cancel(User $user, Sale $sale): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isCashier() && $sale->user_id === $user->id;
    }
}
