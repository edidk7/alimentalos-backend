<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Find\IndexRequest;
use Illuminate\Http\JsonResponse;

class FindController extends Controller
{
    /**
     * Show current devices locations
     *
     * @param IndexRequest $request
     * @return JsonResponse
     */
    public function __invoke(IndexRequest $request)
    {
        $locations = locations()->fetchLastLocationsViaRequest();
        return response()->json($locations,200);
    }
}
