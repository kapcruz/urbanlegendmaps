<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
/**
 * @mixin Request
 */
class StoreUrbanLegendRequest extends FormRequest
{

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Validation error.',
            'errors' => $validator->errors(),
        ], 422));
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => ['required','string','max:255'],
            'description' => 'nullable|string',
            'latitude'    => 'required|numeric',
            'longitude'   => 'required|numeric',
            'country'     => ['required', Rule::in(config('countries'))],
            'city'        => 'required|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Set a title. Title is required.',
            'latitude.required'  => 'Set a latitude. Latitude is required.',
            'longitude.required' => 'Set a longitude. Longitude is required.',
            'country.required'   => 'Set a country. Country is required.',
            'city.required'      => 'Set a city. City is required.'
        ];
    }
}
