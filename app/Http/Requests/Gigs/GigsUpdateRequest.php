<?php

namespace App\Http\Requests\Gigs;

use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GigsUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !$this->company || Company::find($this->company)->user_id === Auth::user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'filled|string|max:100',
            'description' => '',
            'start_date' => 'filled|date|exclude',
            'end_date' => 'filled|date|after:start_date|exclude',
            'pay_per_hour' => 'numeric',
            'status' => 'boolean',
            'company' => 'filled|integer|exists:companies,id|exclude'
        ];
    }
}
