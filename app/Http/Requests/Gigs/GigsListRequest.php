<?php

namespace App\Http\Requests\Gigs;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GigsListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company' => 'filled|string',
            'progress' => 'in:not_started,started,finished',
            'status' => 'boolean',
            'search_string' => 'filled|string'
        ];
    }
}
