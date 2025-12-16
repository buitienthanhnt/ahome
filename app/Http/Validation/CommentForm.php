<?php

namespace App\Http\Validation;

use App\Models\CommentInterface;

class CommentForm extends BaseForm
{
    /**
     * @return array
     */
    protected function rules()
    {
        return [
            CommentInterface::ATTR_EMAIL => 'required|email',
            CommentInterface::ATTR_NAME => 'required',
            CommentInterface::ATTR_CONTENT => 'required'
        ];
    }
}
