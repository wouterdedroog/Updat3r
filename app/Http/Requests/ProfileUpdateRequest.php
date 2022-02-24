<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileUpdateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->user()->id),
            ],
            'name' => [
                'required',
                'string',
                'min:2',
                'max:128'
            ],
            'current_password' => [
                'nullable',
                'required_with_all:password,password_confirmation',
                'current_password',
            ],
            'password' => [
                'nullable',
                'confirmed',
                Password::min(8)->letters()->mixedCase()->symbols()
            ],
        ];
    }
}
