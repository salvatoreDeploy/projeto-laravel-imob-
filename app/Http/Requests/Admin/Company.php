<?php

namespace LaraDev\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class Company extends FormRequest
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
            // Company
            'user' => 'required',
            'social_name' => 'required|min:10|max:191',
            'alias_name' => 'required',
            'document_company' => 'required',
            'document_company_secundary' => 'required',

            //Address
            'zipcode' => 'required|min:8|max:9',
            'street' => 'required',
            'number' => 'required',
            'complement' => '',
            'neighborhood' => 'required',
            'state' => 'required',
            'city' => 'required',
        ];
    }
}
