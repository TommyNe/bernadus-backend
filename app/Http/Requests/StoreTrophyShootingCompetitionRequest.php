<?php

namespace App\Http\Requests;

use App\Models\Competition;
use App\Models\CompetitionType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreTrophyShootingCompetitionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'year' => ['required', 'integer', 'between:1900,2100'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'source_url' => ['nullable', 'url', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*.name' => ['required', 'string', 'max:255'],
            'categories.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'categories.*.results' => ['required', 'array', 'min:1'],
            'categories.*.results.*.person_id' => ['nullable', 'integer', 'exists:people,id'],
            'categories.*.results.*.winner_name' => ['required', 'string', 'max:255'],
            'categories.*.results.*.rank' => ['required', 'integer', 'min:1', 'max:255'],
            'categories.*.results.*.score' => ['nullable', 'numeric', 'min:0'],
            'categories.*.results.*.score_text' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'categories.required' => 'Mindestens eine Kategorie ist erforderlich.',
            'categories.*.name.required' => 'Jede Kategorie benötigt einen Namen.',
            'categories.*.results.required' => 'Jede Kategorie benötigt mindestens ein Ergebnis.',
            'categories.*.results.*.winner_name.required' => 'Jedes Ergebnis benötigt einen Gewinnernamen.',
            'categories.*.results.*.rank.required' => 'Jedes Ergebnis benötigt einen Platz.',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $competitionTypeId = CompetitionType::query()
                    ->where('type_key', 'trophy_shooting')
                    ->value('id');

                if ($competitionTypeId !== null) {
                    $exists = Competition::query()
                        ->where('competition_type_id', $competitionTypeId)
                        ->where('year', $this->integer('year'))
                        ->exists();

                    if ($exists) {
                        $validator->errors()->add('year', 'Für dieses Jahr existiert bereits ein Pokalschießen.');
                    }
                }

                $categoryNames = collect($this->input('categories', []))
                    ->pluck('name')
                    ->filter()
                    ->all();

                if (count($categoryNames) !== count(array_unique($categoryNames))) {
                    $validator->errors()->add('categories', 'Kategorienamen dürfen pro Wettbewerb nur einmal vorkommen.');
                }

                foreach ($this->input('categories', []) as $categoryIndex => $category) {
                    $ranks = collect($category['results'] ?? [])
                        ->pluck('rank')
                        ->filter(static fn (mixed $rank): bool => $rank !== null)
                        ->all();

                    if (count($ranks) !== count(array_unique($ranks))) {
                        $validator->errors()->add("categories.{$categoryIndex}.results", 'Platzierungen dürfen pro Kategorie nur einmal vorkommen.');
                    }
                }
            },
        ];
    }
}
