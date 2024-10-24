<?php 
namespace App\Application\Account\ValidationServices\FieldByField\Handlers;

use App\Domain\Account\User\ValidationRules\EmailValidation;
use App\Domain\Validations\ValidationResult;
use App\Domain\Account\User\ValidationRules\UserValidation;
use App\Domain\Account\Documents\ValidationRules\DocumentValidation;
use App\Application\Account\ValidationServices\FieldByField\AbstractValidateFieldsHandler;
use App\Domain\Account\Repository\UserRepository;

class ValidateBasicUserInfoFieldsHandler extends AbstractValidateFieldsHandler
{
    public function serviceName(): string
    {
        return 'BASIC_USER_INFO';
    }

    public function disponibleFields(): array
    {
        return [
            'firstName',
            'lastName',
            'documentType',
            'documentNumber',
            'birthDate',
            'email'
        ];
    }

    public function __construct(
        private UserValidation $userValidation,
        private DocumentValidation $documentValidation,
        private EmailValidation $emailValidation,
        private UserRepository $userRepository
    )
    {}

    public function exec(array $fields): ValidationResult
    {
        $validationResult = new ValidationResult();

        if(isset($fields['firstName'])) {
            $validationResult->addAnotherValidationResult($this->validateFirstName($fields['firstName']));
        }

        if(isset($fields['lastName'])) {
            $validationResult->addAnotherValidationResult($this->validateLastName($fields['lastName']));
        }

        if(isset($fields['documentType']) && isset($fields['documentNumber'])) {
            $validationResult->addAnotherValidationResult($this->validateDocumentType($fields['documentType'], $fields['documentNumber']));
        }

        if(isset($fields['birthDate'])) {
            $validationResult->addAnotherValidationResult($this->validateBirthDate($fields['birthDate']));
        }

        if(isset($fields['email'])) {
            $validationResult->addAnotherValidationResult($this->validateEmail($fields['email']));
        }

        return $validationResult;
    }

    private function validateFirstName(?string $firstName): ValidationResult
    {
        return $this->userValidation->validateFirstName($firstName);
    }

    private function validateLastName(?string $lastName): ValidationResult
    {
        return $this->userValidation->validateLastName($lastName);
    }

    private function validateDocumentType(?string $documentType, $documentNumber): ValidationResult
    {
        return $this->documentValidation->validate($documentType, $documentNumber);
    }

    private function validateBirthDate(?string $birthDate = ''): ValidationResult
    {
        return $this->userValidation->validateBirthDateYmd($birthDate);
    }

    private function validateEmail(?string $email = ''): ValidationResult
    {
        $validationResult = $this->emailValidation->validateEmail($email);
        if($this->userRepository->isEmailAlreadyRegistered($email)){
            $validationResult->addError('email', 'Este e-mail já está cadastrado, faça o login ou recupere sua senha.');
        };

        return $validationResult;
    }
}