<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
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
                // check if unique or ignore if project isn't provided (create action)
                Rule::unique('projects')->ignore($this->route('project') ?? null),
                'min:6',
                'regex:/^([0-9A-Za-z- ])+$/'
            ]
        ];
    }
}
