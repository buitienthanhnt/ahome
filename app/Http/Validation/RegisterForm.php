<?php

namespace App\Http\Validation;

class RegisterForm extends BaseForm
{
    /**
     * @return array
     */
    protected function rules()
    {
        return [
            'full_name' => 'alpha',
            'email' => 'required|email',
            'password' => 'required|alpha|between:8,32',
            'password_confirmation' => 'required|alpha|confirmed',
        ];
    }
}
