<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class DeleteUrbanLegendRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        $this->merge([
            'uuid' => $this->route('uuid'),
        ]);
    }

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
            'uuid' => 'required|uuid|exists:urban_legends,uuid',
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
            'uuid.required' => 'UUID is required.',
            'uuid.uuid' => 'Invalid UUID format.',
            'uuid.exists' => 'Urban legend not found.',
        ];
    }

}
