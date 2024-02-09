<?php

namespace App\Policies;

use App\Models\Chapter;
use App\Models\Fanfiction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FanfictionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Fanfiction  $fanfiction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Fanfiction $fanfiction)
    {

    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Fanfiction  $fanfiction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Fanfiction $fanfiction)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Fanfiction  $fanfiction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Fanfiction $fanfiction)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Fanfiction  $fanfiction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Fanfiction $fanfiction)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Fanfiction  $fanfiction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Fanfiction $fanfiction)
    {
        //
    }

    public function fanficAccess(User $user, Fanfiction $fanfiction)
    {
        return $user->id === $fanfiction->author_id;
    }

    public function chapterBelongToFanfic(User $user, Fanfiction $fanfiction, ?Chapter $chapter)
    {
        return $fanfiction == $chapter->fanfiction;
    }
}
