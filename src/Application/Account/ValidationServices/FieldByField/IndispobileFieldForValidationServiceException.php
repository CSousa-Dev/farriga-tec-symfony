<?php 
namespace App\Application\Account\ValidationServices\FieldByField;

use Exception;

class IndispobileFieldForValidationServiceException extends Exception
{
    public function __construct(array $fields, string $service)
    {
        $message = "Some fields are not available for validation in the service validation/$service: " . implode(', ', $fields);
        parent::__construct($message, 422);
    }
}