<?php
namespace App\Domain\Account\Services\RegisterUser;

use Throwable;
use App\Domain\Account\User\User;
use App\Domain\Validations\ValidationResult;
use App\Domain\Validations\ValidationConstraintViolationException;

class RegisterUserViolateValidationConstraintException extends ValidationConstraintViolationException
{
    public function __construct(
        private ValidationResult $validationResult,
        private User $user,
        private ?Throwable $previous = null
    )
    {
        parent::__construct($validationResult, $previous);
    }

    public function defaultMessage(): string
    {
        return 'Existem erros de validação no registro do usuário.';
    }

    public function defaultCode(): int
    {
        return 400;
    }
}