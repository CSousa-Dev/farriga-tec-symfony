<?php 
namespace App\Domain\Account\User;

use App\Domain\Account\User\User;

interface IPasswordHasher
{
    public function hash(string $password, User $user): string;
    
}