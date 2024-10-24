<?php 
namespace App\Domain\Account\User;

use App\Domain\Validations\Validable;
use App\Domain\Validations\ValidationResult;
use App\Domain\Account\User\ValidationRules\AddressValidation;
use App\Domain\Validations\ValidationList;

class Address extends Validable
{
    public function __construct(
        private AddressValidation $addressValidationRules,
        public readonly string $street, 
        public readonly string $number,
        public readonly string $neighborhood, 
        public readonly string $city, 
        public readonly string $state, 
        public readonly string $country, 
        public readonly string $zipCode, 
        public readonly ?string $reference = null, 
        public readonly ?string $complement = null)
    {}

    public function validate(): ValidationResult
    {
        return $this->addressValidationRules->validateFromAdrress($this);
    }

    public function validationRules(): ValidationList
    {
        return $this->addressValidationRules->allRules();
    }
}