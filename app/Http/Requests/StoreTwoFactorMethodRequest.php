<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTwoFactorMethodRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'max:32'
            ],
            'two_factor_check' => [
                'required_with:two_factor_secret',
                'digits:6',
            ],
            'two_factor_secret' => [
                'required_without:yubikey_otp',
                'size:32'
            ],
            'yubikey_otp' => [
                'required_without:two_factor_secret',
                'size:44'
            ]
        ];
    }
}
