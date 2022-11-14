<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * A service class for the UserController.
 */
class UserService 
{
    /**
     * @param array<int, string> $data
     * 
     * @return bool
     */
    public function updateProfile($data): bool
    {
        $user = Auth::user();
        
        if ($user->update($data)) {
            return true;
        }
        
        $user->refresh();
        return false;
    }
}