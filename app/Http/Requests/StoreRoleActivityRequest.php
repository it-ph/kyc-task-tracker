<?php

namespace App\Http\Requests;

use App\Traits\ResponseTraits;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreRoleActivityRequest extends FormRequest
{
    use ResponseTraits;
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
            'name' => ['required'],
            'sla' => ['required'],
            // 'frequency' => ['required'],
            // 'schedule' => ['required'],
            // 'function' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Role Name is required.',
            'sla.required' => 'SLA is required.',
            // 'frequency.required' => 'Frequency is required.',
            // 'schedule.required' => 'Schedule is required.',
            // 'function.required' => 'Function is required.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = $this->failedValidationResponse($validator->errors());
        throw new HttpResponseException(response()->json($response, 200));
    }
}
