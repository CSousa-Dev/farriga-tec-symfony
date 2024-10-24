<?php

namespace App\Infra\Doctrine\Repository\Devices;

use Doctrine\Persistence\ManagerRegistry;
use App\Infra\Doctrine\Entity\Devices\SensorTypes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<SensorTypes>
 *
 * @method SensorTypes|null find($id, $lockMode = null, $lockVersion = null)
 * @method SensorTypes|null findOneBy(array $criteria, array $orderBy = null)
 * @method SensorTypes[]    findAll()
 * @method SensorTypes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SensorTypesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SensorTypes::class);
    }

//    /**
//     * @return SensorTypes[] Returns an array of SensorTypes objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SensorTypes
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
