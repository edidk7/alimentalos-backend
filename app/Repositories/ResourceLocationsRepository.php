<?php

namespace App\Repositories;

use App\Events\Location as LocationEvent;
use App\Http\Resources\Location as LocationResource;

class ResourceLocationsRepository
{
    /**
     * Create resource location via request.
     *
     * @return LocationResource
     */
    public function createViaRequest()
    {
        $model = modelLocations()->resolveLocationModel();
        $location = modelLocations()->createLocation($model, request()->all());
        $model->update(["location" => parser()->point(request()->all())]);
        geofences()->checkLocationUsingModelGeofences($model, $location);
        $payload = new LocationResource($location);
        event(new LocationEvent($payload, $model));
        return $payload;
    }
}
