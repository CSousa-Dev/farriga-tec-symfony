<?php 
namespace App\Domain\Account\User\ValidationRules;

use App\Domain\Account\User\Address;
use App\Domain\Validations\Validator;
use App\Domain\Validations\IConstraints;
use App\Domain\Validations\ValidationList;
use App\Domain\Validations\ValidationRule;
use App\Domain\Validations\ValidationMaker;
use App\Domain\Validations\ValidationResult;

class AddressValidation extends ValidationMaker
{
    private Validator $validator;
    private IConstraints $constraints;
    public function __construct(Validator $validator, IConstraints $constraints)
    {   
        $this->validator  = $validator;
        $this->constraints = $constraints;
    }

    public function validateFromAdrress(Address $adress)
    {
        $validationsResult = $this->validateStreet($adress->street);
        $validationsResult->addAnotherValidationResult($this->validateNumber($adress->number));
        $validationsResult->addAnotherValidationResult($this->validateNeighborhood($adress->neighborhood));
        $validationsResult->addAnotherValidationResult($this->validateCity($adress->city));
        $validationsResult->addAnotherValidationResult($this->validateState($adress->state));
        $validationsResult->addAnotherValidationResult($this->validateCountry($adress->country));
        $validationsResult->addAnotherValidationResult($this->validateZipCode($adress->zipCode));
        $validationsResult->addAnotherValidationResult($this->validateReference($adress->reference));
        $validationsResult->addAnotherValidationResult($this->validateComplement($adress->complement));
        return $validationsResult;
    }

    public function validateStreet($street): ValidationResult
    {   
        return $this->validator->validate($this->foreachRuleSetValue($street, ...$this->validateStreetRules()));
    }

    public function validateNumber($number): ValidationResult
    {
        return $this->validator->validate($this->foreachRuleSetValue($number, ...$this->validateNumberRules()));
    }

    public function validateNeighborhood($neighborhood): ValidationResult
    {
        return $this->validator->validate($this->foreachRuleSetValue($neighborhood, ...$this->validateNeighborhoodRules()));
    }

    public function validateCity($number): ValidationResult
    {
        return $this->validator->validate($this->foreachRuleSetValue($number, ...$this->validateCityRules()));
    }

    
    public function validateState($state): ValidationResult
    {
        return $this->validator->validate($this->foreachRuleSetValue($state, ...$this->validateStateRules()));
    }
    
    public function validateCountry($country): ValidationResult
    {
        return $this->validator->validate($this->foreachRuleSetValue($country, ...$this->validateCountryRules()));
    }

    public function validateReference($referece): ValidationResult
    {
        return $this->validator->validate($this->foreachRuleSetValue($referece, ...$this->validateReferenceRules()));
    }

    public function validateComplement($complement): ValidationResult
    {
        return $this->validator->validate($this->foreachRuleSetValue($complement, ...$this->validateComplementRules()));
    }

    public function validateZipCode($zipCode): ValidationResult
    {
        return $this->validator->validate($this->foreachRuleSetValue($zipCode, ...$this->validateZipCodeRules()));
    }
    

    private function validateStreetRules()
    {
        $streetMustBeNotBlanck = new ValidationRule(
            validationRule: $this->constraints->notBlanck(),
            message: 'É obrigatório.',
            field: 'street',
            group: 'homeAddress'
        );

        $streetMaxlength100 = new ValidationRule(
            validationRule: $this->constraints->length(
                max: 100,
            ),
            message: 'Deve ter no máximo 100 caracteres.',
            field: 'street',
            group: 'homeAddress',
            messageType: 'maxMessage'
        );

        return [
            $streetMustBeNotBlanck,
            $streetMaxlength100
        ];
    }

    private function validateNumberRules()
    {
        $mustBeNotNull = new ValidationRule(
            validationRule: $this->constraints->notBlanck(),
            message: 'É obrigatório.',
            field: 'number',
            group: 'homeAddress'
        );

        $maxlength = new ValidationRule(
            validationRule: $this->constraints->length(
                max: 50,
            ),            
            message: 'Deve ter no máximo 50 caracteres.',
            field: 'number',
            group: 'homeAddress',
            messageType: 'maxMessage'
        );

        return [
            $mustBeNotNull,
            $maxlength
        ];
    }

    private function validateNeighborhoodRules()
    {
        $mustBeNotNull = new ValidationRule(
            validationRule: $this->constraints->notBlanck(),
            message: 'É obrigatório.',
            field: 'neighborhood',
            group: 'homeAddress'
        );

        $maxlength = new ValidationRule(
            validationRule: $this->constraints->length(
                max: 50,
            ),
            message: 'Deve ter no máximo 50 caracteres.',
            field: 'neighborhood',
            group: 'homeAddress',
            messageType: 'maxMessage'
        );

        return [
            $mustBeNotNull,
            $maxlength
        ];
    }

    private function validateCityRules()
    {
        $mustBeNotNull = new ValidationRule(
            validationRule: $this->constraints->notBlanck(),
            message: 'É obrigatório.',
            field: 'city',
            group: 'homeAddress'
        );

        $maxlength = new ValidationRule(
            validationRule: $this->constraints->length(
                max: 50,
            ),
            message: 'Deve ter no máximo 50 caracteres.',
            field: 'city',
            group: 'homeAddress',
            messageType: 'maxMessage'
        );

        return [
            $mustBeNotNull,
            $maxlength
        ];
    }

    private function validateStateRules()
    {
        $mustBeNotNull = new ValidationRule(
            validationRule: $this->constraints->notBlanck(),
            message: 'É obrigatório.',
            field: 'state',
            group: 'homeAddress'
        );

        $maxlength = new ValidationRule(
            validationRule: $this->constraints->length(
                max: 30,
            ),
            message: 'Deve ter no máximo 30 caracteres.',
            field: 'state',
            group: 'homeAddress',
            messageType: 'maxMessage'
        );

        return [
            $mustBeNotNull,
            $maxlength
        ];
    }

    private function validateCountryRules()
    {
        $mustBeNotNull = new ValidationRule(
            validationRule: $this->constraints->notBlanck(),
            message: 'É obrigatório.',
            field: 'country',
            group: 'homeAddress'
        );

        $maxlength = new ValidationRule(
            validationRule: $this->constraints->length(
                max: 30,
            ),
            message: 'Deve ter no máximo 30 caracteres.',
            field: 'country',
            group: 'homeAddress',
            messageType: 'maxMessage'
        );

        return [
            $mustBeNotNull,
            $maxlength
        ];
    }
    private function validateReferenceRules()
    {
        $maxlength = new ValidationRule(
            validationRule: $this->constraints->length(
                max: 100,
            ),
            message: 'Deve ter no máximo 100 caracteres.',
            field: 'reference',
            group: 'homeAddress',
            messageType: 'maxMessage'
        );

        return [
            $maxlength
        ];
    }

    private function validateComplementRules()
    {
        $maxlength = new ValidationRule(
            validationRule: $this->constraints->length(
                max: 100,
            ),
            message: 'Deve ter no máximo 100 caracteres.',
            field: 'complement',
            group: 'homeAddress',
            messageType: 'maxMessage'
        );

        return [
            $maxlength
        ];
    }

    private function validateZipCodeRules()
    {
        $notBlanck = new ValidationRule(
            validationRule: $this->constraints->notBlanck(),
            message: 'É obrigatório.',
            group: 'homeAddress',
            field: 'zipCode'
        );

        $maxlength = new ValidationRule(
            validationRule: $this->constraints->length(
                max: 20,
            ),
            message: 'Deve ter no máximo 20 caracteres.',
            field: 'zipCode',
            group: 'homeAddress',
            messageType: 'maxMessage'
        );

        return [
            $notBlanck,
            $maxlength
        ];
    }

    public function allRules(): ValidationList
    {
        $validationList = new ValidationList();
        $validationList->addMultipleRules(...$this->validateStreetRules());
        $validationList->addMultipleRules(...$this->validateNumberRules());
        $validationList->addMultipleRules(...$this->validateNeighborhoodRules());
        $validationList->addMultipleRules(...$this->validateCityRules());
        $validationList->addMultipleRules(...$this->validateStateRules());
        $validationList->addMultipleRules(...$this->validateCountryRules());
        $validationList->addMultipleRules(...$this->validateComplementRules());
        $validationList->addMultipleRules(...$this->validateReferenceRules());
        return $validationList;
    }
}