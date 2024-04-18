<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            "user_name"=>"sometimes|unique:users,user_name",
            "music_bio"=>"sometimes",
            "bank_name"=> "sometimes",
            "account_name"=> "sometimes",
            "account_number"=>"sometimes|min:10",
            "firebase_token" =>"sometimes",
            "profile_image"=>"sometimes|mimes:png,jpg,jpeg,gif",
        ];
    }
}
