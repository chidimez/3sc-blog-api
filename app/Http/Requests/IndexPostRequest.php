<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/** @mixin \Illuminate\Http\Request */
class IndexPostRequest extends FormRequest
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
            'page' => 'integer|min:1',
            'per_page' => 'integer|min:1|max:100',
            'author' => 'sometimes|string',
            'from' => 'sometimes|date',
            'to' => 'sometimes|date|after_or_equal:from',
        ];
    }
}
