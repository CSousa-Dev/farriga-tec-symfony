<?php
namespace App\Domain\Account\User;

use DateTime;
use App\Domain\Validations\Validable;
use App\Domain\Validations\ValidationList;
use App\Domain\Validations\ValidationResult;
use App\Domain\Account\User\ValidationRules\PlainTextPasswordValidation;

class PlainTextPassword extends Validable
{
    private ?string $ownerFirstName = null;
    private ?string $ownerLastName = null;
    private ?DateTime $ownerBirthDate = null;

    public function __construct(
        private string $password,
        private PlainTextPasswordValidation $plainTextValidationRules)
    {
    }

    public function setOwnerData(
        string $ownerFirstName,
        string $ownerLastName,
        DateTime $ownerBirthDate
    )
    {
        $this->ownerFirstName = $ownerFirstName;
        $this->ownerLastName  = $ownerLastName;
        $this->ownerBirthDate = $ownerBirthDate;
    }

    public function password()
    {
        return $this->password;
    }

    public function validate(): ValidationResult
    {
        return $this->plainTextValidationRules->validatePassword($this->password, $this->ownerFirstName, $this->ownerLastName, $this->ownerBirthDate);
    }

    public function validationRules(): ValidationList
    {
        return $this->plainTextValidationRules->allRules();
    }
}