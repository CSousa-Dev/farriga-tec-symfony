<?php
namespace App\Application\Account\RegisterAccount;

use App\Application\Account\Builders\UserBuilder;
use App\Domain\Account\User\PlainTextPassword;
use App\Application\Account\DTOs\RegisterAccountDTO;
use App\Domain\Account\Services\RegisterUser\RegisterUser;
use App\Application\Account\RegisterAccount\ValidateRegisterInputs;
use App\Domain\Account\User\ValidationRules\PlainTextPasswordValidation;

class RegisterAccountService
{
    public function __construct(
        private ValidateRegisterInputs $validateRegisterInputs,
        private RegisterUser $registerUser,
        private UserBuilder $userBuilder,
        private PlainTextPasswordValidation $plainTextPasswordValidation
    ){}

    public function execute(RegisterAccountDTO $registerAccountDto): void
    {
        $this->validateRegisterInputs->execute($registerAccountDto);
        $user = $this->userBuilder->fromRegisterAccountDto($registerAccountDto)->build();

        $this->registerUser->execute(
            new PlainTextPassword(
                $registerAccountDto->plainPassword,
                $this->plainTextPasswordValidation
            ), 
            $user
        );
    }


}