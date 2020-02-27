<?php

namespace App\Policies;

use App\Geofence;
use App\Group;
use App\Pet;
use App\Photo;
use App\Repositories\GroupsRepository;
use App\Repositories\SubscriptionsRepository;
use App\Repositories\UsersRepository;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PetPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the pet.
     *
     * @param User $user
     * @param Pet $pet
     * @return mixed
     */
    public function view(User $user, Pet $pet)
    {
        return $user->is_admin || $pet->is_public || $pet->user_id === $user->id;
    }

    /**
     * Determine whether the user can create pets.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->is_admin || SubscriptionsRepository::can('create', 'pets', $user);
    }

    /**
     * Determine whether the user can update the pet.
     *
     * @param User $user
     * @param Pet $pet
     * @return mixed
     */
    public function update(User $user, Pet $pet)
    {
        return $user->is_admin || $pet->user_id === $user->id;
    }

    /**
     * Determine whether the user can create photo in the pet.
     *
     * @param User $user
     * @param Pet $pet
     * @return mixed
     */
    public function createPhoto(User $user, Pet $pet)
    {
        return $user->can('create', Photo::class) &&
            ($user->is_admin || $pet->is_public || $pet->user_id === $user->id);
    }

    /**
     * Determine whether the user can delete the pet.
     *
     * @param User $user
     * @param Pet $pet
     * @return mixed
     */
    public function delete(User $user, Pet $pet)
    {
        return $user->is_admin || $pet->user_id === $user->id;
    }

    /**
     * Determine whether the user can attach group the pet.
     *
     * @param User $user
     * @param Pet $pet
     * @param Group $group
     * @return mixed
     */
    public function attachGroup(User $user, Pet $pet, Group $group)
    {
        return $user->is_admin ||
            (
                UsersRepository::isProperty($pet, $user) &&
                GroupsRepository::userIsGroupAdmin($user, $group) &&
                !GroupsRepository::modelIsGroupModel($pet, $group)
            );
    }

    /**
     * Determine whether the user can detach group the pet.
     *
     * @param User $user
     * @param Pet $pet
     * @param Group $group
     * @return mixed
     */
    public function detachGroup(User $user, Pet $pet, Group $group)
    {
        return $user->is_admin ||
            (
                UsersRepository::isProperty($pet, $user) &&
                GroupsRepository::userIsGroupAdmin($user, $group) &&
                GroupsRepository::modelIsGroupModel($pet, $group)
            );
    }

    /**
     * Determine whether the user can attach geofence the pet.
     *
     * @param User $user
     * @param Pet $pet
     * @param Geofence $geofence
     * @return mixed
     */
    public function attachGeofence(User $user, Pet $pet, Geofence $geofence)
    {
        return $user->is_admin ||
            (
                UsersRepository::isProperty($pet, $user) &&
                !in_array($pet->id, $geofence->pets->pluck('id')->toArray())
            );
    }

    /**
     * Determine whether the user can detach geofence the pet.
     *
     * @param User $user
     * @param Pet $pet
     * @param Geofence $geofence
     * @return mixed
     */
    public function detachGeofence(User $user, Pet $pet, Geofence $geofence)
    {
        return $user->is_admin ||
            (
                UsersRepository::isProperty($pet, $user) &&
                in_array($pet->id, $geofence->pets->pluck('id')->toArray())
            );
    }
}