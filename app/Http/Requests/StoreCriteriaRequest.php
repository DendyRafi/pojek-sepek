<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCriteriaRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:criterias,name'],
            'type' => ['required', 'string', 'in:maximize,minimize'],
            'weight' => ['required', 'numeric', 'gt:0'],
            'preference_function' => ['required', 'string', 'in:usual,linear,quasi,linear_quasi,level,gaussian'],
            'p' => ['nullable', 'numeric', 'min:0'],
            'q' => ['nullable', 'numeric', 'min:0'],
            's' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
