<?php 
namespace App\Application\Account\ValidationServices\FieldByField;

use Exception;

class InvalidValidationServiceNameException extends Exception
{
    public function __construct(string $service, string $lastService)
    {
        $message = "The validation service $service is invalid, check the available services. Last service is $lastService.";
        parent::__construct($message, 422);
    }
}