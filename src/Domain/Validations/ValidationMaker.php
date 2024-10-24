<?php
namespace App\Domain\Validations;

use App\Domain\Validations\ValidationList;

abstract class ValidationMaker
{
    public abstract function allRules(): ValidationList;

    public function foreachRuleSetValue($value, ValidationRule ...$validationRules): ValidationList
    {
        $validationList = new ValidationList();
        foreach($validationRules as $rule) {
            $rule->setValue($value);
            $validationList->addRule($rule);
        }
        return $validationList;
    }
}