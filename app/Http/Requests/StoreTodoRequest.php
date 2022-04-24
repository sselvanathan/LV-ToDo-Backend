<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class StoreTodoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape(['id' => "array", 'name' => "string"])] public function rules(): array
    {
        return [
            'id' => ['required',
                Rule::exists('todos')
                    ->where('user_id', auth()->id())
            ],
            'name' => 'required',
        ];
    }
}
