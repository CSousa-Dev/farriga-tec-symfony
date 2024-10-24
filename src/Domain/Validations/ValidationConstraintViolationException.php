<?php
namespace App\Domain\Validations;

use Exception;
use Throwable;
use App\Domain\Validations\ValidationResult;
use DomainException;

abstract class ValidationConstraintViolationException Extends DomainException
{
    public function __construct(
        private ValidationResult $validationResult,
        Throwable $previous = null
    )
    {
        parent::__construct($this->defaultMessage(), $this->defaultCode(), $previous);
    }

    public function errors()
    {
        return $this->validationResult->errors();
    }
}