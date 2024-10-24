<?php
namespace App\Domain\Account\User;

use DateTime;
use App\Domain\Validations\Validable;
use App\Domain\Validations\ValidationList;
use App\Domain\Validations\ValidationResult;
use App\Domain\Account\User\IVerificationCode;
use App\Domain\Account\User\ValidationRules\EmailValidation;

class Email extends Validable
{
    public function __construct(
        public readonly string $address, 
        private EmailValidation $emailValidationRules, 
        private ?IVerificationCode $verificationCode = null, 
        private ?DateTime $verifiedAt = null)
    {}

    public function lastGeneratedCode()
    {
        return $this->verificationCode->lastestCode();
    }

    public function lastestVerifiedAt(): ?DateTime
    {
        return $this->verifiedAt;
    }

    /**
     * Quando for necessário verificar uma conta de e-mail, um código de verificação é enviado para o e-mail.
     *
     * @param string $newCode
     * @return boolean
     */
    public function verify($newCode): bool
    {
        if ($this->verificationCode->checkCode($newCode)){
            $this->verifiedAt = new DateTime();
            return true;
        }        
        return false;
    }

    public function validate(): ValidationResult
    {
        return $this->emailValidationRules->validateEmail($this->address);
    }

    public function validationRules(): ValidationList
    {
        return $this->emailValidationRules->allRules();
    }
}