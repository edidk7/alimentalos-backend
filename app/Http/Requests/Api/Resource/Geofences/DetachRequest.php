<?php

namespace App\Http\Requests\Api\Resource\Geofences;

use App\Http\Requests\AuthorizedRequest;

class DetachRequest extends AuthorizedRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user('api')->can('detachGeofence', [$this->route('resource'), $this->route('geofence')]);
    }
}
