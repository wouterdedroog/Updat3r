<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateUpdateRequest extends UpdateRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return parent::rules() + [
                'version' => [
                    'required',
                    'regex:/^([0-9A-Za-z-\. ])+$/',
                    Rule::unique('updates')->where(function ($query) {
                        return $query->where('project_id', $this->route('project')->id);
                    })->ignore($this->route('update')->id),
                ]
            ];
    }
}
