<?php 
namespace App\Domain\Validations;

use App\Domain\Validations\ValidationList;
use App\Domain\Validations\ValidationResult;

abstract class Validator
{
    public function validate(ValidationList $validationList): ValidationResult
    {
        $validationResult = new ValidationResult();
        $resultados = [];
        foreach ($validationList->validations() as $validationRule) {
            $errorsList = $this->validateRule($validationRule);
            $resultados[] = $errorsList;
            $validationResult->addValidatedField($validationRule->field);
            if (!$errorsList->hasErrors()) {
                continue;
            }

            $validationResult = $this->addAllErrorsInList($errorsList, $validationRule, $validationResult);
        }
        return $validationResult;
    }


    private function addAllErrorsInList(ErrorsList $errorsList, ValidationRule $validationRule, $validationResult): ValidationResult
    {
        foreach($errorsList->errors() as $error) {
            $validationResult->addError($validationRule->field, $error, $validationRule->group);
        }

        return $validationResult;
    }

    public abstract function validateRule(ValidationRule $validationRule): ErrorsList;
}