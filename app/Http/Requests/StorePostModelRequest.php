<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostModelRequest extends FormRequest
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
            "type" => "required|in:text,image,link,video",
            'content' => 'nullable|string|max:500',
            'file' => 'sometimes|mimes:jpeg,png,gif,jpg,mp4,mov,webm,avi,mkv,wmv,flv',
        ];
    }
}
