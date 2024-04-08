<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTimelineRequest extends FormRequest
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
            'project_id'=>'required',
            'timelines'=>'required|array',
            'timelines.*.title'=>'required|string',
            'timelines.*.start_date'=>'required|date',
            'timelines.*.end_date'=>'required|date'
        ];
    }
}
