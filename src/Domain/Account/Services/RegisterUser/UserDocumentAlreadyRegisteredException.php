<?php 
namespace App\Domain\Account\Services\RegisterUser;

use Throwable;
use DomainException;
use App\Domain\Account\User\User;

class UserDocumentAlreadyRegisteredException Extends DomainException
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
        return "O document {$this->user->document()->type()} de número {$this->user->document()->number()} já está cadastrado.";
    }

    public function defaultCode(): int
    {
        return 400;
    }
}