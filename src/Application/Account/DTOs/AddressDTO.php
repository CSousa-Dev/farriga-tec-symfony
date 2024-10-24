<?php 
namespace App\Application\Account\DTOs;

use DocsMaker\Attributes\Component\ComponentProp;
use DocsMaker\Attributes\Component\ComponentSchema;

#[ComponentSchema(name: 'Address', description: 'Home Address DTO')]
class AddressDTO
{
    #[ComponentProp]
    public readonly ?string $street;

    #[ComponentProp]
    public readonly ?string $number;

    #[ComponentProp]
    public readonly ?string $neighborhood;

    #[ComponentProp]
    public readonly ?string $city;

    #[ComponentProp]
    public readonly ?string $state;
    
    #[ComponentProp]
    public readonly ?string $country;
    
    #[ComponentProp]
    public readonly ?string $zipCode;
    
    #[ComponentProp]
    public readonly ?string $complement;
    
    #[ComponentProp]
    public readonly ?string $reference;
    

    public function __construct(
        ?string $street,
        ?string $number,
        ?string $neighborhood,
        ?string $city,
        ?string $state,
        ?string $country,
        ?string $zipCode,
        ?string $complement = null,
        ?string $reference = null
    ) {
        $this->street = $street;
        $this->number = $number;
        $this->neighborhood = $neighborhood;
        $this->city = $city;
        $this->state = $state;
        $this->country = $country;
        $this->zipCode = $zipCode;
        $this->complement = $complement;
        $this->reference = $reference;
    }
    
}