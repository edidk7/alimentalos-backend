<?php

namespace App\Http\Controllers\Api\Resource\Groups;

use App\Group;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Resource\Groups\AttachRequest;
use Illuminate\Http\JsonResponse;

class AttachController extends Controller
{
    /**
     * Attach resource in Group.
     *
     * @param AttachRequest $request
     * @param $resource
     * @param Group $group
     * @return JsonResponse
     */
    public function __invoke(AttachRequest $request, $resource, Group $group)
    {
        $resource->groups()->attach($group->id, [
            'status' => Group::ATTACHED_STATUS,
            'is_admin' => $request->has('is_admin') ? $request->input('is_admin') : false,
        ]);
        return response()->json([], 200);
    }
}