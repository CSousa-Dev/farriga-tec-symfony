<?php

namespace App\Infra\Doctrine\Repository\Account;

use Doctrine\Persistence\ManagerRegistry;
use App\Infra\Doctrine\Entity\Account\Address;
use App\Domain\Account\User\Address as DomainAddress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Address>
 *
 * @method Adress|null find($id, $lockMode = null, $lockVersion = null)
 * @method Adress|null findOneBy(array $criteria, array $orderBy = null)
 * @method Adress[]    findAll()
 * @method Adress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Address::class);
    }

    public function createAddressFromDomainAddress(DomainAddress $address)
    {
        $entityAddress = new Address();
        $entityAddress->setStreet($address->street);
        $entityAddress->setNumber($address->number);
        $entityAddress->setNeighborhood($address->neighborhood);
        $entityAddress->setCity($address->city);
        $entityAddress->setState($address->state);
        $entityAddress->setCountry($address->country);
        $entityAddress->setZipCode($address->zipCode);
        $entityAddress->setReference($address->reference);
        $entityAddress->setComplement($address->complement);
        return $entityAddress;
    }

//    /**
//     * @return Adress[] Returns an array of Adress objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Adress
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
