<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * A procedure responsible for a user registration
     * @param RegistrationRequest $request 
     * @return JsonResponse
     */
    public function register(RegistrationRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $isUserCreated = User::create($data);
        
        return response()->json([
            'response' => $isUserCreated 
            ? 'You have successfully registered!'
            : 'The server was unable to process your registration request, please contact support.'
        ], status: $isUserCreated ? 200 : 500);
    }
}
