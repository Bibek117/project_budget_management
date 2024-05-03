<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRecordRequest extends FormRequest
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
        $id = $this->route('record'); 
    return [
            'timeline_id'=>'required',
            'execution_date' => 'required|date',
            'code'=>'required|unique:records,code,'.$id,
            'transactions' => 'required|array|min:2',
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
            'transactions.*.desc.required' => 'Description field cannot be empty',
            'transactions.*.amount.required' => 'Amount can not be empty',
            'transactions.*.COA.required'=> 'Please select a COA'
        ];
    }
}
