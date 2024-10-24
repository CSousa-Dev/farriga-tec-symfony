<?php
namespace App\Application;

use Exception;
use App\Domain\Validations\ValidationResult;

class InvalidInputException extends Exception
{
    public function __construct(
        $message = "Houve um erro de validação nos dados de entrada.", 
        private readonly ?ValidationResult $validationResult = null
    )
    {
        parent::__construct($message);
    }

    public function validationResult(): ?ValidationResult
    {
        return $this->validationResult;
    }
}