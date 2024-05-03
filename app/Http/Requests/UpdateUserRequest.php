<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
        $id = $this->route('user');
        return [
            'username' => 'required|string',
            'email' => 'required|email|unique:users,email,'.$id,
            'phone' => 'required',
            'contacttype_id' => 'array',
            'project_id' => 'array'
        ];
    }
}
