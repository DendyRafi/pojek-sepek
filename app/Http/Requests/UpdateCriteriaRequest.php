<?php

namespace App\Http\Requests;

use App\Models\Criteria;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCriteriaRequest extends FormRequest
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
        $criteriaId = $this->route('criteria') instanceof Criteria
            ? $this->route('criteria')->id
            : $this->route('criteria');

        return [
            'name' => ['required', 'string', 'max:255', 'unique:criterias,name,'.$criteriaId],
            'type' => ['required', 'string', 'in:maximize,minimize'],
            'weight' => ['required', 'numeric', 'gt:0'],
            'preference_function' => ['required', 'string', 'in:usual,linear,quasi,linear_quasi,level,gaussian'],
            'p' => ['nullable', 'numeric', 'min:0'],
            'q' => ['nullable', 'numeric', 'min:0'],
            's' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
