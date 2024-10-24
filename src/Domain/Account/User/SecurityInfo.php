<?php 
namespace App\Domain\Account\User;

class SecurityInfo
{
    public function __construct(
        private readonly bool $isAuthenticated,
        private array $roles,
    ){}

    public function isAuth(): bool
    {
        return $this->isAuthenticated;
    }
    public function roles(): array
    {
        return $this->roles;
    }

}