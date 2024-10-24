<?php 

namespace App\Application\Account\ValidationServices\FieldByField\Handlers;

use App\Domain\Validations\ValidationResult;
use App\Domain\Account\User\ValidationRules\PlainTextPasswordValidation;
use App\Application\Account\ValidationServices\FieldByField\AbstractValidateFieldsHandler;

class ValidatePlainPasswordFieldsHandler extends AbstractValidateFieldsHandler
{
    public function disponibleFields(): array
    {
        return [
            'password',
            'passwordConfirmation'
        ];
    }

    public function serviceName(): string
    {
        return 'PASSWORD';
    }

    public function __construct(
        private PlainTextPasswordValidation $passwordValidation,
    )
    {}

    public function exec(array $fields): ValidationResult
    {
        $validationResult = new ValidationResult();

        if(isset($fields['password'])) {
            $validationResult->addAnotherValidationResult($this->passwordValidation->validatePassword($fields['password']));
        }

        if(isset($fields['passwordConfirmation']) && isset($fields['password'])) {
            $validationResult->addAnotherValidationResult($this->passwordValidation->validateConfirmation($fields['password'],  $fields['passwordConfirmation']));
        }

        return $validationResult;
    }


}