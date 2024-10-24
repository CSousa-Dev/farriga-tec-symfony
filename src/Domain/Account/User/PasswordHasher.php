<?php 
namespace App\Domain\Account\User;

interface PasswordHasher
{
    public function hash(User $user, string $password): string;
    public function verify(string $password, string $hash): bool;
}