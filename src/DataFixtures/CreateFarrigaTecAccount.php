<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Application\Account\DTOs\DocumentDTO;
use App\Application\Account\DTOs\RegisterAccountDTO;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Application\Account\RegisterAccount\RegisterAccountService;

class CreateFarrigaTecAccount extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private RegisterAccountService $registerAccountService
    ){}
    public function load(ObjectManager $manager): void
    {
        $accountDto = new RegisterAccountDTO(
            'Farriga',
            'Tec',
            'farriga@tec.com.br',
            'F@at&c@2024',
            '2000-01-01',
            new DocumentDTO(
                '651.797.760-07',
                'CPF'
            )
        );

        $this->registerAccountService->execute($accountDto);
    }

    public function getDependencies(): array
    {
        return [
            CreateDocumentTypes::class
        ];
    }
}
