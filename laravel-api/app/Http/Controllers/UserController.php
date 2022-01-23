<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function find(string $uuid): JsonResponse
    {
        try {
            return response()->json([ "data" => $this->userService->getByUuid($uuid) ]);
        } catch (Exception $exception) {
            return response()->json([ "error" => $exception->getMessage() ],500);
        }
    }

    public function getAll(): JsonResponse
    {
        try {
            return response()->json([ "data" => $this->userService->getAll() ]);
        } catch (Exception $exception) {
            return response()->json([ "error" => $exception->getMessage() ],500);
        }
    }
}
