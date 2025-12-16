<?php
namespace App\Http\Validation;

use App\Models\CommentInterface;

class CommentLikeForm extends BaseForm
{
    protected function rules()
    {
        return [
            CommentInterface::PARAM_ACTION => 'required'
        ];
    }
}
