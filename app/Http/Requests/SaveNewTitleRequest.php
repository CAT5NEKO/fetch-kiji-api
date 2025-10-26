<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveNewTitleRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'url' => [
                'required',
                'url',
                'max:512',
                Rule::unique('articles', 'url'),
            ],
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'source' => [
                'nullable',
                'string',
                'max:255',
            ],
            'published_at' => [
                'nullable',
                'date',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'url.required' => 'URLは必須です。',
            'url.url' => 'URL形式が不正です。',
            'url.max' => 'URLは512文字以内で入力してください。',
            'url.unique' => 'このURLは既に登録されています。',
            'title.required' => 'タイトルは必須です。',
            'title.max' => 'タイトルは255文字以内で入力してください。',
        ];
    }
}
