<?php

namespace App\Policies;

use App\Geofence;
use App\Photo;
use App\Repositories\SubscriptionsRepository;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GeofencePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the geofence.
     *
     * @param User $user
     * @param Geofence $geofence
     * @return mixed
     */
    public function view(User $user, Geofence $geofence)
    {
        return $user->is_admin || $geofence->is_public || $geofence->user_id === $user->id || $geofence->user_id === $user->user_id;
    }

    /**
     * Determine whether the user can create geofences.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->is_admin || SubscriptionsRepository::can('create', 'geofences', $user);
    }

    /**
     * Determine whether the user can update the geofence.
     *
     * @param User $user
     * @param Geofence $geofence
     * @return mixed
     */
    public function update(User $user, Geofence $geofence)
    {
        return $user->is_admin || $geofence->user_id === $user->id;
    }

    /**
     * Determine whether the user can create photo in the geofence.
     *
     * @param User $user
     * @param Geofence $geofence
     * @return mixed
     */
    public function createPhoto(User $user, Geofence $geofence)
    {
        return $user->can('create', Photo::class) &&
            ($user->is_admin || $geofence->user_id === $user->id || $geofence->is_public);
    }

    /**
     * Determine whether the user can delete the geofence.
     *
     * @param User $user
     * @param Geofence $geofence
     * @return mixed
     */
    public function delete(User $user, Geofence $geofence)
    {
        return $user->is_admin || $geofence->user_id === $user->id;
    }
}