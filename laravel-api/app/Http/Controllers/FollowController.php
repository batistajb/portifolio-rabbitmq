<?php

namespace App\Http\Controllers;

use App\Http\Requests\FollowRequest;
use App\Http\Requests\UnfollowRequest;
use App\Models\User;
use App\Services\FollowService;
use Exception;
use Illuminate\Http\JsonResponse;

class FollowController extends Controller
{
    private FollowService $followService;

    public function __construct(FollowService $followService)
    {
        $this->followService = $followService;
    }

    public function followers(User $user): JsonResponse
    {
        try {
            return response()->json([ "data" => $this->followService->followers($user->uuid) ]);
        } catch (Exception $exception) {
            return response()->json([ "error" => $exception->getMessage() ],500);
        }
    }

    public function follows(User $user): JsonResponse
    {
        try {
            return response()->json([ "data" => $this->followService->follows($user->uuid) ]);
        } catch (Exception $exception) {
            return response()->json([ "error" => $exception->getMessage() ],500);
        }
    }

    public function follow(FollowRequest $request, User $user): JsonResponse
    {
        try {
            return response()->json([
                "data" => $this->followService->follow([
                        "user_uuid" => $request->get('user_uuid'),
                        "user_uuid_follow" => $user->uuid,
                        "user_name_follow" => $user->name
                    ])
            ]);
        } catch (Exception $exception) {
            return response()->json([ "error" => $exception->getMessage() ],500);
        }
    }

    public function unfollow(UnfollowRequest $request, User $user): JsonResponse
    {
        try {
            return response()->json([
                "data" => $this->followService->unfollow([
                    "user_uuid" => $request->get('user_uuid'),
                    "user_name_follow" => $user->name,
                    "user_uuid_follow" => $user->uuid,
                ])
            ]);
        } catch (Exception $exception) {
            return response()->json([ "error" => $exception->getMessage() ],500);
        }
    }
}
