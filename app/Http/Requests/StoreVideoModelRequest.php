<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVideoModelRequest extends FormRequest
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
            "can_post" => "required|in:no,yes,Yes,No,YES,NO",
            'caption' => 'nullable|string|max:500',
            'video' => 'required|mimes:mp4,mov,webm,avi,mkv,wmv,flv',
        ];
    }
}
