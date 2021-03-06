<?php


namespace App\Repositories;


use App\Contracts\Resource;

class UploadRepository
{
    /**
     * Check if request has a model photo pending to upload.
     *
     * @param Resource $model
     */
    public function check(Resource $model)
    {
        if (request()->has('photo')) {
            $photo = photos()->createPhotoViaRequest();
            $model->update([
                'photo_uuid' => $photo->uuid,
                'photo_url' => config('storage.path') . $photo->photo_url,
                'location' => parser()->pointFromCoordinates(input('coordinates')),
            ]);
            $model->photos()->attach($photo->uuid);
        }

    }
}
