<?php

namespace App\Policies;

use App\Models\User;

class BookPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user): bool
    {
        return $user->is_admin;
    }

    public function toggleBorrowedStatus(User $user, $book): bool
    {
        // If the book is not borrowed, any authenticated user can borrow it
        if (! $book->is_borrowed) {
            return true;
        }

        // If the book is borrowed, only the borrower or an admin can return it
        return $user->is_admin || (int) $book->borrowed_by === $user->id;
    }
}
