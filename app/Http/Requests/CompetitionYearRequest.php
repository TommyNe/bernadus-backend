<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CompetitionYearRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'year' => ['required', 'integer', 'between:1900,2100'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function validationData(): array
    {
        return [
            ...$this->all(),
            'year' => $this->route('year'),
        ];
    }
}
