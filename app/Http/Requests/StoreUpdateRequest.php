<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class StoreUpdateRequest extends UpdateRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return parent::rules() + [
                'version' => [
                    'required',
                    'regex:/^([0-9A-Za-z-\. ])+$/',
                    Rule::unique('updates')->where(function ($query) {
                        return $query->where('project_id', $this->route('project')->id);
                    })
                ],
                'updatefile' => [
                    'required',
                    'file',
                    'max:2000'
                ]
            ];
    }
}
