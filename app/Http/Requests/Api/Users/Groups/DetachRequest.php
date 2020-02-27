<?php

namespace App\Http\Requests\Api\Users\Groups;

use Illuminate\Foundation\Http\FormRequest;

class DetachRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user('api')->can('detachGroup', [
            $this->route('user'), $this->route('group')
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}