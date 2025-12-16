<?php
namespace App\Http\Validation;

use App\Models\ViewSource;
use App\Models\ViewSourceInterface;

class PaperLikeForm extends BaseForm
{
    protected function rules()
    {
        return [
            ViewSourceInterface::PARAM_TYPE => 'required|alpha',
            ViewSourceInterface::PARAM_ACTION => 'required|alpha'
        ];
    }  
}
