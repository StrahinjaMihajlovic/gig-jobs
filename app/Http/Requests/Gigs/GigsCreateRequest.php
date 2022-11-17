<?php

namespace App\Http\Requests\Gigs;

use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GigsCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Company::find($this->company)->user_id === Auth::user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:100',
            'description' => 'required|string',
            'start_date' => 'required|date|exclude',
            'end_date' => 'required|date|after:start_date|exclude',
            'pay_per_hour' => 'filled|numeric',
            'status' => 'filled|boolean',
            'company' => 'required|integer|exists:companies,id|exclude'
        ];
    }
}
