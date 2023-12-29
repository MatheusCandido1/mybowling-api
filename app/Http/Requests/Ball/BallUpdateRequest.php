<?php

namespace App\Http\Requests\Ball;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BallUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'weight' => 'required',
            'color' => 'required',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(
            [
                'message' => 'Validation Error',
                'data'  => $validator->errors()
            ]
        ));
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'weight.required' => 'Weight is required',
            'color.required' => 'Color is required',
        ];
    }
}
