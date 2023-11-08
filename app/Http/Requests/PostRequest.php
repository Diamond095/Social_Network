<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title'=>'required|string',
            'content'=>'required|string',
            'image_id'=>'nullable|integer|exists:post_images,id'
        ];
    }
    public function messages(): array
    {
        return [
            'title.required' => 'Заголовок не должен быть пустым',
            'content.required' => 'Текст поста не должен быть пустым',
            'title.string' => 'Заголовок не должен быть текстом',
            'content.string' => 'Текст поста не должен быть текстом',
        ];
    }
}
