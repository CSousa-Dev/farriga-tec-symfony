<?php 
namespace App\Domain\Account\Services\RegisterUser;

use Throwable;
use DomainException;
use App\Domain\Account\User\User;

class UserEmailAlreadyRegisteredException Extends DomainException
{
    public function __construct(
        private User $user,
        Throwable $previous = null
    )
    {
        parent::__construct($this->defaultMessage(), $this->defaultCode(), $previous);
    }
    public function defaultMessage(): string
    {
        return "O e-mail {$this->user->email()->address} já está cadastrado.";
    }

    public function defaultCode(): int
    {
        return 409;
    }
}