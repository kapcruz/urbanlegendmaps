<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateUrbanLegendRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Validation error.',
            'errors'  => $validator->errors(),
        ], 422));
    }

    public function authorize(): bool
    {
        return true;
    }

   
    public function rules(): array
    {
        $uuid = $this->route('uuid');

        return [
            'title'       => ['sometimes','string','max:255'],
            'description' => ['sometimes','nullable','string'],
            'latitude'    => ['sometimes','numeric'],
            'longitude'   => ['sometimes','numeric'],
            'country'     => ['sometimes', Rule::in(config('countries'))],
            'city'        => ['sometimes','string','max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'     => 'Set a title. Title is required.',
            'latitude.numeric'   => 'Latitude must be a number.',
            'longitude.numeric'  => 'Longitude must be a number.',
            'country.in'         => 'Invalid country code.',
        ];
    }
}
