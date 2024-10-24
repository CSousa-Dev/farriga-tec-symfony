<?php
namespace App\Domain\Account\User;

use DateTime;
use LogicException;
use InvalidArgumentException;
use App\Domain\Account\User\Email;
use App\Domain\Validations\Validable;
use App\Domain\Account\Documents\Document;
use App\Domain\Validations\ValidationList;
use App\Domain\Validations\ValidationResult;
use App\Domain\Account\User\ValidationRules\UserValidation;
use SecurityInfo;

class User extends Validable {

    public function __construct(
        private             string $firstName,
        private             string $lastName,
        private             Document $document,
        private             UserValidation $userValidation,
        private             DateTime $birthDate,
        private             Email $email,
        private readonly    ?SecurityInfo $securityInfo = null,
        private             ?Address $address = null,
        public  readonly    ?string $id = null, 
    )
    {}
    
    public function homeAddress(): ?Address
    {
        return $this->address;
    }

    public function changeHomeAddress(Address $address): void
    {
        $this->address = $address;
        $this->validate();
    }

    public function changeDocument(Document $document): void
    {
        $this->document = $document;
        $this->validate();
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function changeEmail(Email $email): void
    {
        $this->email = $email;
        $this->validate();
    }

    public function verifyEmail($newCode): bool
    {
        if(is_null($newCode))
            throw new InvalidArgumentException('Erro ao verificar o e-mail, não foi fornecido código de verificação.');

        if(is_null($this->email->lastGeneratedCode()))
            throw new LogicException('Erro ao verificar o e-mail, primeiro é necessário ter o ultímo código gerado para que seja possível realizar a comparação.');

        return $this->email->verify($newCode);
    }


    public function firstName(): string
    {
        return $this->firstName;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }

    public function birthDate(): string
    {
        return $this->birthDate->format('Y-m-d');
    }

    public function document(): Document 
    {
        return $this->document;
    }

    public function fullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function validate(): ValidationResult
    {
        $userValidationResult = $this->userValidation->validateFromUser($this);
        $userValidationResult->addAnotherValidationResult($this->email->validate());
        $userValidationResult->addAnotherValidationResult($this->document->validate());

        if(!is_null($this->address))
            $userValidationResult->addAnotherValidationResult($this->address->validate());

        return $userValidationResult;
    }

    public function validationRules(): ValidationList
    {
        $validationList = $this->userValidation->allRules();
        $validationList->addMultipleRules(...$this->email->validationRules()->validations());
        $validationList->addMultipleRules(...$this->document->validationRules()->validations());
        $validationList->addMultipleRules(...$this->address->validationRules()->validations());

        return $validationList;
}}