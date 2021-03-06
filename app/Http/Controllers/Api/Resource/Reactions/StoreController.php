<?php

namespace App\Http\Controllers\Api\Resource\Reactions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Resource\Reactions\StoreRequest;
use Illuminate\Http\JsonResponse;

class StoreController extends Controller
{
    /**
     * @OA\Post(
     *      path="/{resource}/reactions",
     *      operationId="createResourceReaction",
     *      tags={"Resources"},
     *      summary="Create resource reaction.",
     *      description="Returns the empty array as JSON response.",
     *      @OA\Parameter(
     *          name="resource",
     *          description="Resource class type",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Resource instance reaction created successfully"
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     * )
     * Create resource reaction.
     *
     * @param StoreRequest $request
     * @param $resource
     * @return JsonResponse
     */
    public function __invoke(StoreRequest $request, $resource)
    {
        likes()->updateLike($resource->getLoveReactant(), authenticated()->getLoveReacter(), input('type'));
        return response()->json([],200);
    }
}
