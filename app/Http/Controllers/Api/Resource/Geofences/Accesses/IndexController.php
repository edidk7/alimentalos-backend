<?php

namespace App\Http\Controllers\Api\Resource\Geofences\Accesses;

use App\Geofence;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Resource\Geofences\Accesses\IndexRequest;
use Illuminate\Http\JsonResponse;

class IndexController extends Controller
{
    /**
     * @OA\Get(
     *      path="/{resource}/geofences/{geofence}/accesses",
     *      operationId="getResourceGeofencesAccessesPaginated",
     *      tags={"Resources"},
     *      summary="Get resource specific geofence accesses paginated.",
     *      description="Returns the resource accesses instances paginated by a default quantity, payload includes pagination links and stats.",
     *      @OA\Parameter(
     *          name="resource",
     *          description="Resource class type",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="geofence",
     *          description="Geofence identifier",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Resource accesses retrieved successfully"
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource doesn't implements has geofences trait"),
     * )
     * Get resource specific geofence accesses paginated.
     *
     * @param IndexRequest $request
     * @param $resource
     * @param Geofence $geofence
     * @return JsonResponse
     */
    public function __invoke(IndexRequest $request, $resource, Geofence $geofence)
    {
        $accesses = $resource
            ->accesses()
            ->with(['accessible', 'geofence', 'first_location', 'last_location'])
            ->where([['geofence_uuid', $geofence->uuid]])
            ->latest()
            ->paginate(20);
        return response()->json($accesses,200);
    }
}
