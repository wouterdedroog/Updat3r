<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'critical' => [
                'required',
                'boolean'
            ],
            'public' => [
                'required',
                'boolean'
            ]
        ];
    }
}
