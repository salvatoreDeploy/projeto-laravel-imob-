<?php

namespace LaraDev\Policies;

use LaraDev\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserProfilePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \LaraDev\User  $user
     * @param  \LaraDev\User  $model
     * @return mixed
     */
    public function view(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \LaraDev\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \LaraDev\User  $user
     * @param  \LaraDev\User  $model
     * @return mixed
     */

    public function update(User $authenticatedUser, User $user)
    {
        return $authenticatedUser->id === $user->id || $authenticatedUser->admin === 1;
    }
    /**
     * Determine whether the user can delete the model.
     *
     * @param  \LaraDev\User  $user
     * @param  \LaraDev\User  $model
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \LaraDev\User  $user
     * @param  \LaraDev\User  $model
     * @return mixed
     */
    public function restore(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \LaraDev\User  $user
     * @param  \LaraDev\User  $model
     * @return mixed
     */
    public function forceDelete(User $user, User $model)
    {
        //
    }
}
