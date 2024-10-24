<?php
namespace App\Application\Account\Builders;

use App\Application\Account\DTOs\AddressDTO;
use App\Domain\Account\User\Address;
use App\Domain\Account\User\ValidationRules\AddressValidation;

class AddressBuilder 
{
    private string $street;
    private string $number;
    private string $neighborhood;
    private string $city;
    private string $state;
    private string $country;
    private string $zipCode;
    private ?string $complement = null;
    private ?string $reference = null;
    
    public function __construct(
        private AddressValidation $addressValidation
    ){}

    public function build(): Address
    {
        return new Address(
            addressValidationRules: $this->addressValidation,
            street: $this->street,
            number: $this->number,
            complement: $this->complement,
            neighborhood: $this->neighborhood,
            city: $this->city,
            state: $this->state,
            reference: $this->reference,
            country: $this->country,
            zipCode: $this->zipCode,
        );
    }
    
    public function withStreet($street): AddressBuilder
    {
        $this->street = $street;
        return $this;
    }

    public function withNumber($number): AddressBuilder
    {
        $this->number = $number;
        return $this;
    }

    public function withComplement($complement): AddressBuilder
    {
        $this->complement = $complement;
        return $this;
    }

    public function withNeighborhood($neighborhood): AddressBuilder
    {
        $this->neighborhood = $neighborhood;
        return $this;
    }

    public function withCity($city): AddressBuilder
    {
        $this->city = $city;
        return $this;
    }

    public function withState($state): AddressBuilder
    {
        $this->state = $state;
        return $this;
    }

    public function withCountry($country): AddressBuilder
    {
        $this->country = $country;
        return $this;
    }

    public function withZipCode($zipCode): AddressBuilder
    {
        $this->zipCode = $zipCode;
        return $this;
    }

    public function withReference($reference): AddressBuilder
    {
        $this->reference = $reference;
        return $this;
    }

    public function fromAddressDto(AddressDTO $addressDto): AddressBuilder
    {
        $this->street           = $addressDto->street;
        $this->number           = $addressDto->number;
        $this->complement       = $addressDto->complement;
        $this->neighborhood     = $addressDto->neighborhood;
        $this->city             = $addressDto->city;
        $this->state            = $addressDto->state;
        $this->country          = $addressDto->country;
        $this->zipCode          = $addressDto->zipCode;
        $this->reference        = $addressDto->reference;
        return $this;
    }

    public function isReady()
    {
        if(
            empty($this->street) ||
            empty($this->number) ||
            empty($this->neighborhood) ||
            empty($this->city) ||
            empty($this->state) ||
            empty($this->country) ||
            empty($this->zipCode)
        )
            return false;
            
        return true;
    }
}