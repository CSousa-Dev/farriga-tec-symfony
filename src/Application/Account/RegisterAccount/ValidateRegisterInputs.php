<?php
namespace App\Application\Account\RegisterAccount;

use DateTime;
use App\Application\InvalidInputException;
use App\Domain\Validations\ValidationResult;
use App\Application\Account\DTOs\RegisterAccountDTO;
use App\Domain\Account\User\ValidationRules\UserValidation;
use App\Domain\Account\User\ValidationRules\EmailValidation;
use App\Domain\Account\User\ValidationRules\AddressValidation;
use App\Domain\Account\Documents\ValidationRules\DocumentValidation;
use App\Domain\Account\User\ValidationRules\PlainTextPasswordValidation;

class ValidateRegisterInputs
{
    private ValidationResult $validationResult;
    public function __construct(
        private UserValidation $userValidation,
        private DocumentValidation $documentValidation, 
        private PlainTextPasswordValidation $plainTextPasswordValidation, 
        private EmailValidation $emailValidation,
        private AddressValidation $addressValidation
    )
    {
        $this->validationResult = new ValidationResult();
    }

    public function execute(RegisterAccountDTO $registerAccountDTO,
    )
    {
        $this->validateUserBasicData($registerAccountDTO);
        $this->validateEmail($registerAccountDTO);
        $this->validateDocument($registerAccountDTO);
        $this->validateAddress($registerAccountDTO);
        $this->validatePlainTextPassword($registerAccountDTO);
        if($this->validationResult->hasErrors())
        {
            throw new InvalidInputException(
                'Houve erros na validação dos dados informados para cadastro.'
                ,$this->validationResult
            );
        }
    }

    private function validateUserBasicData(RegisterAccountDTO $registerAccountDTO)
    {
        $this->validationResult->addAnotherValidationResult($this->userValidation->validateFirstName($registerAccountDTO->firstName));
        $this->validationResult->addAnotherValidationResult($this->userValidation->validateLastName($registerAccountDTO->lastName));
        $this->validationResult->addAnotherValidationResult($this->userValidation->validateBirthDateYmd($registerAccountDTO->birthDate));
    }

    private function validateEmail(RegisterAccountDto $registerAccountDTO)
    {
        $this->validationResult->addAnotherValidationResult($this->emailValidation->validateEmail($registerAccountDTO->email));
    }

    private function validateDocument(RegisterAccountDto $registerAccountDTO)
    {
        $this->validationResult->addAnotherValidationResult($this->documentValidation->validate($registerAccountDTO->document->type, $registerAccountDTO->document->number));
    }

    private function validateAddress(RegisterAccountDto $registerAccountDTO)
    {
        $addressDto = $registerAccountDTO->addressDto;
        if(is_null($addressDto)) return;

        $this->validationResult->addAnotherValidationResult($this->addressValidation->validateStreet($addressDto->street));
        $this->validationResult->addAnotherValidationResult($this->addressValidation->validateNumber($addressDto->number));
        $this->validationResult->addAnotherValidationResult($this->addressValidation->validateComplement($addressDto->complement));
        $this->validationResult->addAnotherValidationResult($this->addressValidation->validateNeighborhood($addressDto->neighborhood));
        $this->validationResult->addAnotherValidationResult($this->addressValidation->validateCountry($addressDto->country));
        $this->validationResult->addAnotherValidationResult($this->addressValidation->validateCity($addressDto->city));
        $this->validationResult->addAnotherValidationResult($this->addressValidation->validateState($addressDto->state));
        $this->validationResult->addAnotherValidationResult($this->addressValidation->validateZipCode($addressDto->zipCode));
        $this->validationResult->addAnotherValidationResult($this->addressValidation->validateComplement($addressDto->complement));
    }

    private function validatePlainTextPassword(RegisterAccountDto $registerAccountDTO)
    {
        $this->validationResult->addAnotherValidationResult($this->plainTextPasswordValidation->validatePassword(
                password: $registerAccountDTO->plainPassword,
                firstName : $registerAccountDTO->firstName,
                lastName : $registerAccountDTO->lastName,
                birthDate : new DateTime($registerAccountDTO->birthDate)
            )
        );
    }
}