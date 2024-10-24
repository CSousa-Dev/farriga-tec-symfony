<?php
namespace App\Domain\Account\Services\RegisterUser;

use DateTime;
use App\Domain\Account\User\User;
use App\Domain\Account\User\PlainTextPassword;
use App\Domain\Account\Repository\UserRepository;
use App\Domain\Account\Services\RegisterUser\UserEmailAlreadyRegisteredException;
use App\Domain\Account\Services\RegisterUser\UserDocumentAlreadyRegisteredException;
use App\Domain\Account\Services\RegisterUser\RegisterUserViolateValidationConstraintException;
use App\Domain\Account\User\IPasswordHasher;

class RegisterUser
{
    private User $user;
    public function __construct(
        private UserRepository $userRepository,
        private IPasswordHasher $passwordHasher
    ){}

    public function execute(PlainTextPassword $plainTextPassword, User $user,

    ): void
    {
        $this->user = $user;
        $plainTextPassword->setOwnerData(
            $this->user->firstName(),
            $this->user->lastName(),
            new DateTime($this->user->birthDate())
        );

        $this->validateConstraints($plainTextPassword);
        $this->checkUserEmailAlreadyRegistered();
        $this->checkUserDocumentAlreadyRegistered();
        $this->userRepository->registerNewUser(
            $this->user,
            $this->passwordHasher->hash($plainTextPassword->password(), $this->user)
        );
    }

    private function validateConstraints($plainTextPassword)
    {
        $validationResult = $this->user->validate();
        $validationResult->addAnotherValidationResult($plainTextPassword->validate());
        if($validationResult->hasErrors())
            throw new RegisterUserViolateValidationConstraintException($validationResult, $this->user);
    }

    private function checkUserEmailAlreadyRegistered()
    {
        if($this->userRepository->isEmailAlreadyRegistered($this->user))
            throw new UserEmailAlreadyRegisteredException($this->user);
    }

    private function checkUserDocumentAlreadyRegistered()
    {
        if($this->userRepository->isDocumentAlreadyRegistered($this->user))
            throw new UserDocumentAlreadyRegisteredException($this->user);
    }
}