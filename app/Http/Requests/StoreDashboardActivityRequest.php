<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDashboardActivityRequest extends FormRequest
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
            'name' => ['required','unique:dashboard_activities,name']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Dashboard Activity Name is required.',
            'name.unique' => 'Dashboard Activity Name already exists.',
        ];
    }
}
