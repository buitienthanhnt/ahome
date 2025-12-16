<?php

namespace App\Http\Validation;

class RegisterUser extends BaseForm
{
    /**
     * @return array
     */
    protected function rules()
    {
        return [
            'email' => 'required|email',
            'name' => 'required',
            'password' => 'required',
            'passwordConfirm' => 'required'
        ];
    }
}
