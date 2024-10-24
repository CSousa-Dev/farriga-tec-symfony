<?php 
namespace App\Application\Account\ValidationServices\FieldByField\Handlers;

use App\Application\Account\ValidationServices\FieldByField\AbstractValidateFieldsHandler;
use App\Domain\Account\User\ValidationRules\AddressValidation;
use App\Domain\Validations\ValidationResult;

class ValidateAddressFieldsHandler extends AbstractValidateFieldsHandler
{
    public function serviceName(): string
    {
        return 'ADDRESS';
    }

    public function disponibleFields(): array
    {
        return [
            'street',
            'number',
            'complement',
            'neighborhood',
            'city',
            'state',
            'country',
            'zipCode',
            'reference'
        ];
    }

    public function __construct(
        private AddressValidation $addressValidation
    )
    {}

    public function exec(array $fields): ValidationResult
    {
        $validationResult = new ValidationResult();

        if(isset($fields['street'])) {
            $validationResult->addAnotherValidationResult($this->validateStreet($fields['street']));
        }

        if(isset($fields['number'])) {
            $validationResult->addAnotherValidationResult($this->validateNumber($fields['number']));
        }

        if(isset($fields['complement'])) {
            $validationResult->addAnotherValidationResult($this->validateComplement($fields['complement']));
        }

        if(isset($fields['neighborhood'])) {
            $validationResult->addAnotherValidationResult($this->validateNeighborhood($fields['neighborhood']));
        }

        if(isset($fields['city'])) {
            $validationResult->addAnotherValidationResult($this->validateCity($fields['city']));
        }

        if(isset($fields['state'])) {
            $validationResult->addAnotherValidationResult($this->validateState($fields['state']));
        }

        if(isset($fields['country'])) {
            $validationResult->addAnotherValidationResult($this->validateCountry($fields['country']));
        }

        if(isset($fields['zipCode'])) {
            $validationResult->addAnotherValidationResult($this->validateZipCode($fields['zipCode']));
        }

        if(isset($fields['reference'])) {
            $validationResult->addAnotherValidationResult($this->validateReference($fields['reference']));
        }

        if($validationResult->hasErrors()) {
            $groupedErrors = $validationResult->errors()['homeAddress']; //Grouped Address information;
            $validateFields = $validationResult->validatedFields();

            $flatValidationResult = new ValidationResult();

            foreach($groupedErrors as $field => $errors) 
            {
                array_map(function($error) use ($field, $flatValidationResult) {
                    $flatValidationResult->addError($field, $error);
                }, $errors);
            }

            foreach($validateFields as $field) 
            {
                $flatValidationResult->addValidatedField($field);
            }
            
            return $flatValidationResult;
        }

        return $validationResult;
    }

    private function validateStreet(?string $street): ValidationResult
    {
        return $this->addressValidation->validateStreet($street);
    }

    private function validateNumber(?string $number): ValidationResult
    {
        return $this->addressValidation->validateNumber($number);
    }

    private function validateComplement(?string $complement): ValidationResult
    {
        return $this->addressValidation->validateComplement($complement);
    }

    private function validateNeighborhood(?string $neighborhood): ValidationResult
    {
        return $this->addressValidation->validateNeighborhood($neighborhood);
    }

    private function validateCity(?string $city): ValidationResult
    {
        return $this->addressValidation->validateCity($city);
    }

    private function validateState(?string $state): ValidationResult
    {
        return $this->addressValidation->validateState($state);
    }

    private function validateCountry(?string $country): ValidationResult
    {
        return $this->addressValidation->validateCountry($country);
    }

    private function validateZipCode(?string $zipCode): ValidationResult
    {
        return $this->addressValidation->validateZipCode($zipCode);
    }

    private function validateReference(?string $reference): ValidationResult
    {
        return $this->addressValidation->validateReference($reference);
    }
}