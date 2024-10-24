<?php
namespace App\Domain\Validations;

abstract class Validable
{
    public function isValid()
    {
        $validationResult = $this->validate();
        return !$validationResult->hasErrors();
    }
    
    public function validationErrors()
    {
        $validationResult = $this->validate();
        return $validationResult->errors();
    }
    public abstract function validate(): ValidationResult;
    public abstract function validationRules(): ValidationList;
}