<?php

namespace App\Http\Controllers\Api\Users\Groups;

use App\Group;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Users\Groups\InviteRequest;
use App\User;
use Illuminate\Http\JsonResponse;

class InviteController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param InviteRequest $request
     * @param User $user
     * @param Group $group
     * @return JsonResponse
     */
    public function __invoke(InviteRequest $request, User $user, Group $group)
    {
        // TODO - Reduce number of lines of InviteController
        // @body move into repository method as createViaRequest.
        if (
            $user->groups()
                ->where('group_uuid', $group->uuid)
                ->where('status', Group::REJECTED_STATUS)
                ->exists()
        ) {
            $user->groups()->updateExistingPivot($group->uuid, [
                'status' => Group::PENDING_STATUS,
                'is_admin' => $request->has('is_admin') ? $request->input('is_admin') : false,
            ]);
        } else {
            $user->groups()->attach($group->uuid, [
                'status' => Group::PENDING_STATUS,
                'is_admin' => $request->has('is_admin') ? $request->input('is_admin') : false,
            ]);
        }
        return response()->json([], 200);
    }
}
