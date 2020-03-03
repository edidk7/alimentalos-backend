<?php

namespace App\Repositories;

use App\Photo;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class UsersRepository
{
    public static function updateUserViaRequest(Request $request, User $user)
    {
        UploadRepository::checkPhotoForUpload($request, $user);
        $user->load('user', 'photo');
        $user->update([
            'email' => FillRepository::fillMethod($request, 'email', $user->email),
            'name' => FillRepository::fillMethod($request, 'name', $user->name),
            'is_public' => FillRepository::fillMethod($request, 'is_public', $user->is_public),
        ]);
        return $user;
    }

    /**
     * Create user via request.
     *
     * @param Request $request
     * @param Photo $photo
     * @return mixed
     */
    public static function createUserViaRequest(Request $request)
    {
        $photo = PhotoRepository::createPhotoViaRequest($request);
        $user = User::create([
            'user_uuid' => $request->user('api')->uuid,
            'photo_uuid' => $photo->uuid,
            'photo_url' => config('storage.path') . $photo->photo_url,
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'password' => bcrypt($request->input('password')),
            'is_public' => $request->input('is_public'),
            'location' => LocationRepository::parsePointFromCoordinates($request->input('coordinates')),
        ]);
        $user->photos()->attach($photo->uuid);
        event(new Registered($user));
        $user->load('photo', 'user');
        return $user;
    }

    /**
     * Check if userA is same of userB
     *
     * @param object|User $userA
     * @param object|User $userB
     * @return bool
     */
    public static function sameUser(User $userA, User $userB)
    {
        return $userA->uuid === $userB->uuid;
    }

    /**
     * Check if userA has same owner of userB
     *
     * @param object|User $userA
     * @param object|User $userB
     * @return bool
     */
    public static function sameOwner(User $userA, User $userB)
    {
        return $userA->user_uuid === $userB->user_uuid;
    }

    /**
     * Check if userA is owner of userB
     *
     * @param object|User $userA
     * @param object|User $userB
     * @return bool
     */
    public static function isOwner(User $userA, User $userB)
    {
        return $userA->uuid === $userB->user_uuid;
    }

    /**
     * Check if userA is worker of userB
     *
     * @param object|User $userA
     * @param object|User $userB
     * @return bool
     */
    public static function isWorker(User $userA, User $userB)
    {
        return $userA->user_uuid === $userB->uuid;
    }

    /**
     * Check if device is property of user
     *
     * @param object|Model $model
     * @param object|User $user
     * @return bool
     */
    public static function isProperty(Model $model, User $user)
    {
        return $model->user_uuid === $user->uuid;
    }
}
