<?php
namespace App\Application\Account\DTOs;

use App\Application\Account\DTOs\AddressDTO;
use App\Application\Account\DTOs\DocumentDTO;
use DocsMaker\Attributes\Component\ComponentProp;
use DocsMaker\Attributes\Component\ComponentSchema;

#[ComponentSchema(name: 'User', description: 'User DTO')]
class RegisterAccountDTO
{
    #[ComponentProp(description: 'The user first name', required: true)]
    public ?string $firstName;

    #[ComponentProp(description: 'The user last name', required: true)]
    public ?string $lastName;

    #[ComponentProp(description: 'The user email', required: true)]
    public ?string $email;

    #[ComponentProp(description: 'The user birth date', required: true, format: 'YYYY-mm-dd')]
    public ?string $birthDate;

    #[ComponentProp(description: 'The user plain password, check /constraints/password for information about the password rules', required: true)]
    public ?string $plainPassword;

    #[ComponentProp(description: 'The user document information', required: true)]
    public ?DocumentDTO $documentDTO;

    #[ComponentProp(description: 'The user address information')]
    public ?AddressDTO $addressDTO;

    
    public function __construct(
        ?string $firstName,
        ?string $lastName,
        ?string $email,
        ?string $plainPassword,
        ?string $birthDate,
        public DocumentDTO $document,
        public ?AddressDTO $addressDto = null
    ){
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->plainPassword = $plainPassword;
        $this->birthDate = $birthDate;
        $this->documentDTO = $document;
        $this->addressDTO = $addressDto;
    }
    
}