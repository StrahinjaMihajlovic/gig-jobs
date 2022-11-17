<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(protected UserService $service) {}

    /**
     * Returns authenticated user's data.
     * @return JsonResponse
     */
    public function showProfile(): JsonResponse
    {
        return response()->json(new UserResource(Auth::user()));
    }

    /**
     * Updates the authenticated user.
     * @param UserRequest $request
     * 
     * @return JsonResponse
     */
    public function updateProfile(UserRequest $request): JsonResponse 
    {
        $updateStatus = $this->service->updateProfile($request->validated());

        return response()->json([ 
                'user' => new UserResource(Auth::user()),
                'status' => $updateStatus ? 'Success' : 'Failed'
            ], $updateStatus ? 200 : 500);
    }

}
