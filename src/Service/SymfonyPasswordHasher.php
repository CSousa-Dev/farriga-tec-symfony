<?php 
namespace App\Service;
use App\Domain\Account\User\User;
use App\Domain\Account\User\IPasswordHasher;
use App\Infra\Doctrine\Entity\Security\UserSecurityInfo;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SymfonyPasswordHasher implements IPasswordHasher
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHash
    ){}
    
    public function hash(string $password, User $user ): string
    {
        $securityUser = new UserSecurityInfo();
        return $this->passwordHash->hashPassword($securityUser, $password);
    }
}