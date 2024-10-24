<?php
namespace App\Domain\Account\Repository;

use App\Domain\Account\User\PlainTextPassword;
use App\Domain\Account\User\User;

interface UserRepository
{
    public function registerNewUser(User $user, string $hashedPassword): void;
    public function isDocumentAlreadyRegistered(User $user): bool;
    public function isEmailAlreadyRegistered(User | string $email): bool;
}