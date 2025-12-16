<?php
namespace App\Http\Validation;

class LoginForm extends BaseForm
{
    /**
     * @return array
     */
    protected function rules()
    {
        return [
            'email'    => 'required|email',
            'password' => 'required|alpha'
        ];
    }
}
