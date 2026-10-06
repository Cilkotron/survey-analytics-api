<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SurveyResponsePostRequest extends FormRequest
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
            'survey_id' => 'required|exists:surveys,id',
            'survey_member_id' => 'required|exists:survey_members,id',
            'answers' => 'required|array',
            'duration_seconds' => 'required|integer|min:1',
            'completion_status' => 'required|in:completed,partial,abandoned',
            'incentive_paid' => 'nullable|numeric|min:0',
        ];
    }
}
