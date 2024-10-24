<?php

namespace App\DataFixtures;

use App\Infra\Doctrine\Entity\Account\DocumentType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CreateDocumentTypes extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $documentType = new DocumentType();
        $documentType->setType('CPF');
        $manager->persist($documentType);
        $manager->flush();
    }
}
