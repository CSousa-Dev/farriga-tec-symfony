<?php
namespace App\Domain\Account\User\ValidationRules;

use DateTime;
use App\Domain\Account\User\User;
use App\Domain\Validations\Validator;
use App\Domain\Validations\IConstraints;
use App\Domain\Validations\ValidationList;
use App\Domain\Validations\ValidationRule;
use App\Domain\Validations\ValidationMaker;
use App\Domain\Validations\ValidationResult;

class UserValidation extends ValidationMaker
{
    public function __construct(
        private Validator $validator, 
        private IConstraints $constraints
    )
    {}

    public function validateFromUser(User $user): ValidationResult
    {
        $validationResult = $this->validateFirstName($user->firstName());
        $validationResult->addAnotherValidationResult($this->validateLastName($user->lastName()));
        $validationResult->addAnotherValidationResult($this->validateBirthDateYmd($user->birthDate()));
        return $validationResult;
    }

    public function validateFirstName(?string $firstName): ValidationResult
    {
        $validationRules = $this->foreachRuleSetValue(
            $firstName,
            ...$this->firstNameValidationRules()
        );

        return $this->validator->validate($validationRules);
    }

    public function validateLastName(?string $lastName): ValidationResult
    {
        $validationRules = $this->foreachRuleSetValue(
            $lastName,
            ...$this->lastNameValidationRules()
        );

        return $this->validator->validate($validationRules);
    }

    public function validateBirthDateYmd(?string $birthDateYmd): ValidationResult
    {
        $validationRules = $this->foreachRuleSetValue(
            $birthDateYmd,
            ...$this->birthDateValidationRules()
        );


        $birthDate = is_null($birthDateYmd) ? false : DateTime::createFromFormat('Y-m-d', $birthDateYmd);
        $isValidDate = ($birthDate && $birthDate->format('Y-m-d') == $birthDateYmd);

        if($isValidDate) {
            $minAgeRule = $this->minimumAgeIs12();
            $minAgeRule->setValue($birthDate);
            $validationRules->addRule($minAgeRule);
        }
        
        return $this->validator->validate($validationRules);
    }

    private function firstNameValidationRules()
    {   
        $nameCannotBeEmpty = new ValidationRule(
            validationRule: $this->constraints->notNull(), 
            message: 'É obrigatório.',
            field: 'firstName'
        );

        $nameMustHaveAtLeastTreeCharacters = new ValidationRule(
            validationRule: $this->constraints->length(
                min: 3, 
            ), 
            messageType: 'minMessage',
            field: 'firstName',
            message: 'Deve ter no mínimo 3 caracteres.'
        );

        $nameMustHaveMaxTenCharacters = new ValidationRule(
            validationRule: $this->constraints->length(
                max: 10, 
            ), 
            messageType: 'maxMessage',
            field: 'firstName',
            message:  'Deve ter no máximo 10 caracteres.'
        );

        $nameMustDontHaveNumbers = new ValidationRule(
            validationRule: $this->constraints->regex(
                pattern: '/[0-9]/', 
                match: false,
            ), 
            field: 'firstName',
            message: 'Não pode conter números.'
        );

        return [
            $nameCannotBeEmpty,
            $nameMustHaveAtLeastTreeCharacters,
            $nameMustHaveMaxTenCharacters,
            $nameMustDontHaveNumbers
        ];
    }

    private function lastNameValidationRules()
    {   
        $nameCannotBeEmpty = new ValidationRule(
            validationRule: $this->constraints->notNull(), 
            field: 'lastName',
            message: 'É obrigatório.'
        );

        $nameMustHaveAtLeastTreeCharacters = new ValidationRule(
            validationRule: $this->constraints->length(
                min: 3
            ), 
            messageType: 'minMessage',
            message: 'Deve ter no mínimo 3 caracteres.',
            field: 'lastName'
        );

        $nameMustHaveMaxTenCharacters = new ValidationRule(
            validationRule: $this->constraints->length(
                max: 10
            ),         
            messageType: 'maxMessage',
            message: 'Deve ter no máximo 10 caracteres.',
            field: 'lastName'
        );

        $nameMustDontHaveNumbers = new ValidationRule(
            validationRule: $this->constraints->regex(
                pattern: '/[0-9]/', 
                match: false,
            ), 
            message: 'Não pode conter números.',
            field: 'lastName'
        );

        return [
            $nameCannotBeEmpty,
            $nameMustHaveAtLeastTreeCharacters,
            $nameMustHaveMaxTenCharacters,
            $nameMustDontHaveNumbers
        ];
    }

    private function minimumAgeIs12()
    {
        return new ValidationRule(
            validationRule: $this->constraints->lessThanOrEqual(
                value: '-12 years',
            ), 
            message: 'A idade mínima para criar uma conta é de 12 anos. Crie uma conta com a ajuda de um responsável.',
            field: 'birthDate'
        );
    }

    private function birthDateValidationRules()
    {
        $birthDateCannotBeEmpty = new ValidationRule(
            validationRule: $this->constraints->notNull(), 
            message: 'É obrigatório.',
            field: 'birthDate'
        );

        $birthDateMustBeValidDate = new ValidationRule(
            validationRule: $this->constraints->isDate(), 
            field: 'birthDate',
            message: 'Deve ser uma data em formato válido.'
        );

        $isRequired = new ValidationRule(
            validationRule: $this->constraints->notBlanck(),
            message: 'É obrigatória.',
            field: 'birthDate'
        );

        return [
            $birthDateCannotBeEmpty,
            $birthDateMustBeValidDate,
            $isRequired 
        ];
    }

    public function allRules(): ValidationList
    {
        $validationList = new ValidationList();
        $validationList->addMultipleRules(...$this->firstNameValidationRules());
        $validationList->addMultipleRules(...$this->lastNameValidationRules());
        $validationList->addMultipleRules(...$this->birthDateValidationRules());
        return $validationList;
    }
}