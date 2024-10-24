<?php

namespace App\Infra\Doctrine\Repository\Security;

use Doctrine\Persistence\ManagerRegistry;
use App\Infra\Doctrine\Entity\Security\ApiToken;
use App\Infra\Doctrine\Entity\Security\UserSecurityInfo;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @extends ServiceEntityRepository<UserSecurityInfo>
 *
 * @method UserSecurityInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserSecurityInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserSecurityInfo[]    findAll()
 * @method UserSecurityInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserSecurityInfoRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserSecurityInfo::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof UserSecurityInfo) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function addApiToken(UserSecurityInfo $userSecurityInfo, ApiToken $apiToken): UserSecurityInfo
    {
        $userSecurityInfo->addApiToken($apiToken);
        $this->getEntityManager()->persist($userSecurityInfo);
        $this->getEntityManager()->flush();
        return $userSecurityInfo;
    }

//    /**
//     * @return UserSecurityInfo[] Returns an array of UserSecurityInfo objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserSecurityInfo
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
