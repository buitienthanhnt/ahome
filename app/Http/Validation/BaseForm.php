<?php
namespace App\Http\Validation;

use App\Http\Exception\FormValidationException;
use Illuminate\Support\Facades\Validator;

abstract class BaseForm
{
    /**
     * @var Validator
     */
    protected $validation;


    public function __construct(){}

    /**
     * @param array $formData
     *
     * @throws FormValidationException
     */
    public function validate(array $formData)
    {
        // Instantiate validator instance by factory
        $this->validation = Validator::make($formData, $this->rules());

        // Validate
        if ($this->validation->fails()) {
            throw new FormValidationException('Validation Failed', $this->getValidationErrors());
        }

        return true;
    }

    /**
     * @return MessageBag
     */
    protected function getValidationErrors()
    {
        return $this->validation->errors();
    }

    /**
     * @return array
     */
    abstract protected function rules();
}
