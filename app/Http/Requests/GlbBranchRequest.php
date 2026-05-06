<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GlbBranchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'CB_Code' => 'required',
            'CB_FullName' => 'required',
            'CB_Address' => 'required',
            'CB_Phone' => 'required',
            'CB_City' => 'required',
            'CB_WhCode' => 'required'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'CB_Code.required' => 'Branch Code is required',
            'CB_FullName.required' => 'Branch Name is required',
            'CB_Address.required' => 'Branch Address is required',
            'CB_Phone.required' => 'Phone is already used',
            'CB_City.required' => 'City is required',
            'CB_WhCode.required' => 'Warehouse Code is required'
        ];
    }
}
