<?php
namespace App\Application\Account\ValidationServices\FieldByField;

use App\Domain\Validations\ValidationResult;
use App\Application\Account\ValidationServices\FieldByField\AbstractValidateFieldsHandler;
use App\Application\Account\ValidationServices\FieldByField\Handlers\ValidateAddressFieldsHandler;
use App\Application\Account\ValidationServices\FieldByField\Handlers\ValidateBasicUserInfoFieldsHandler;
use App\Application\Account\ValidationServices\FieldByField\Handlers\ValidatePlainPasswordFieldsHandler;

class FieldByFieldValidationService
{
    private AbstractValidateFieldsHandler $handler;
    public function __construct(
        ValidateBasicUserInfoFieldsHandler $validateFieldsBasicUserInfoHandler,
        ValidatePlainPasswordFieldsHandler $validatePlainPasswordFieldsHandler,
        ValidateAddressFieldsHandler $validateAddressFieldsHandler,
    )
    {
        $this->handler = $validateFieldsBasicUserInfoHandler;
        $this->handler->setNextHandler($validateAddressFieldsHandler->setNextHandler($validatePlainPasswordFieldsHandler));
    }

    public function execute(string $serviceName, array $fields): ValidationResult | null
    {
        return $this->handler->handle($serviceName, $fields);
    }
}