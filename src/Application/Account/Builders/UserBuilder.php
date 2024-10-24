<?php 
namespace App\Application\Account\Builders;

use DateTime;
use App\Domain\Account\User\User;
use App\Domain\Account\User\Email;
use App\Domain\Account\User\Address;
use App\Domain\Account\Documents\Document;
use App\Application\Account\DTOs\AddressDTO;
use App\Application\Account\Builders\AddressBuilder;
use App\Application\Account\DTOs\RegisterAccountDTO;
use App\Domain\Account\User\ValidationRules\UserValidation;
use App\Domain\Account\User\ValidationRules\EmailValidation;
use App\Domain\Account\Documents\ValidationRules\DocumentValidation;
use App\Domain\Account\User\ValidationRules\PlainTextPasswordValidation;

class UserBuilder 
{
    private ?string $id = null;
    private string $firstName;
    private string $lastName;
    private DateTime $birthDate;
    private string $email;
    private string $plainPassword;
    private string $documentNumber;
    private string $documentType;

    public function __construct(
        private UserValidation $userValidation,
        private DocumentValidation $documentValidation,
        private PlainTextPasswordValidation $plainTextPasswordValidation,
        private EmailValidation $emailValidation,
        private AddressBuilder $addressBuilder
    ){}

    public function withEmail($email): UserBuilder
    {
        $this->email = $email;
        return $this;
    }

    public function withFirstName($firstName): UserBuilder
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function withLastName($lastName): UserBuilder
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function withBirthDate($birthDate): UserBuilder
    {
        $this->birthDate = new DateTime($birthDate);
        return $this;
    }

    public function withPlainPassword($plainPassword): UserBuilder
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    public function withDocumentNumber($documentNumber): UserBuilder
    {
        $this->documentNumber = $documentNumber;
        return $this;
    }

    public function withDocumentType($documentType): UserBuilder
    {
        $this->documentType = $documentType;
        return $this;
    }

    public function withAddress(AddressDTO $addressDto): UserBuilder
    {    
        $this->addressBuilder->withCity($addressDto->city)
            ->withComplement($addressDto->complement)
            ->withCountry($addressDto->country)
            ->withNeighborhood($addressDto->neighborhood)
            ->withNumber($addressDto->number)
            ->withState($addressDto->state)
            ->withStreet($addressDto->street)
            ->withZipCode($addressDto->zipCode);
    
        return $this;
    }

    public function fromRegisterAccountDto(RegisterAccountDTO $registerAccountDto): UserBuilder
    {
        $this->withFirstName($registerAccountDto->firstName)
            ->withLastName($registerAccountDto->lastName)
            ->withEmail($registerAccountDto->email)
            ->withPlainPassword($registerAccountDto->plainPassword)
            ->withBirthDate($registerAccountDto->birthDate)
            ->withDocumentNumber($registerAccountDto->document->number)
            ->withDocumentType($registerAccountDto->document->type);

        if($registerAccountDto->addressDTO instanceof AddressDTO)
            $this->withAddress($registerAccountDto->addressDTO);
       
        return $this;
    }

    public function build(): User
    {
        if($this->addressBuilder->isReady())
            return new User(
                id: $this->id,
                firstName: $this->firstName,
                lastName: $this->lastName,
                document: new Document(
                    $this->documentValidation,
                    $this->documentNumber,
                    $this->documentType
                ),
                userValidation: $this->userValidation,
                birthDate: $this->birthDate,
                email: new Email($this->email, $this->emailValidation),
                address: $this->addressBuilder->build()
        );


        return new User(
            id: $this->id,
            firstName: $this->firstName,
            lastName: $this->lastName,
            document: new Document(
                $this->documentValidation,
                $this->documentNumber,
                $this->documentType
            ),
            userValidation: $this->userValidation,
            birthDate: $this->birthDate,
            email: new Email($this->email, $this->emailValidation)
        );
    }
}