<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'string|max:30',
            'last_name' => 'string|max:30',
            'email' => [
                'email',
                Rule::unique('users')->ignore(Auth::user()->id)
            ],
            'string|unique:users',
            'password' => [
                'string',
                Password::min(8)
                ->mixedCase()
                ->numbers()
            ],
            'repeated_password' => 'required_with:password|same:password|exclude'
        ];
    }
}
