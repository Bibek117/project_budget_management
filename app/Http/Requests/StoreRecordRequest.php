<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRecordRequest extends FormRequest
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
            'project_id' => 'required',
            'execution_date' => 'required|date',
            'code' => 'required|unique:records,code',
            'transactions' => 'required|array|min:2',
            'transactions.*.contact_id'=>'required',
            'transactions.*.desc' => 'required|string',
            'transactions.*.amount' => 'required|numeric',
            'transactions.*.coa_id' => 'required'
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
            'project_id.required' => 'Please select a project',
            'execution_date.required' => 'Date can not be empty',
        ];
    }
}
