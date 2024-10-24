<?php 
namespace App\Domain\Account\User\ValidationRules;

use App\Domain\Validations\Validator;
use App\Domain\Validations\IConstraints;
use App\Domain\Validations\ValidationList;
use App\Domain\Validations\ValidationRule;
use App\Domain\Validations\ValidationMaker;
use App\Domain\Validations\ValidationResult;

class EmailValidation extends ValidationMaker
{
    private IConstraints $constraints;
    private Validator $validator;
    public function __construct(IConstraints $constraints, Validator $validator)
    {
        $this->constraints = $constraints;
        $this->validator   = $validator;
    }

    public function validateEmail($address): ValidationResult
    {
        return $this->validator->validate($this->foreachRuleSetValue($address, ...$this->emailValidationRules()));
    }

    public function emailValidationRules()
    {
        return [
            new ValidationRule(
                validationRule: $this->constraints->email(),
                message: 'Precisa ser um e-mail válido.',
                field: 'email'
            ),
            new ValidationRule(
                validationRule: $this->constraints->notBlanck(),
                message: 'É obrigatório.',
                field: 'email'
            )
        ];
    }

    public function allRules(): ValidationList
    {
       return new ValidationList(...$this->emailValidationRules());
    }
}