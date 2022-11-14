<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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
        $isUserCreated = User::create($data);
        
        return response()->json([
            'response' => $isUserCreated 
            ? 'You have successfully registered!'
            : 'The server was unable to process your registration request, please contact support.'
        ], status: $isUserCreated ? 200 : 500);
    }

    /**
     * A procedure that handles user logic
     * @param Request $request
     * @throws ValidationException
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'device' => 'required|string'
        ]);
     
        $user = User::where('email', $request->email)->first();
     
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided email/password combo do not belong to any user.'],
            ]);
        }
     
        return response()->json([
            'token' => $user->createToken($request->device)->plainTextToken
        ]);
    }
}
