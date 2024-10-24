<?php

namespace App\Infra\Doctrine\Repository\Account;

use DateTimeImmutable;
use App\Domain\Account\User\User;
use Doctrine\Persistence\ManagerRegistry;
use App\Infra\Doctrine\Entity\Security\UserSecurityInfo;
use App\Infra\Doctrine\Entity\Account\User as EntityUser;
use App\Infra\Doctrine\Repository\Account\EmailRepository;
use App\Infra\Doctrine\Repository\Account\AddressRepository;
use App\Infra\Doctrine\Repository\Account\DocumentTypeRepository;
use App\Infra\Doctrine\Repository\Security\UserSecurityInfoRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Domain\Account\Repository\UserRepository as UserRepositoryInterface;

/**
 * @extends ServiceEntityRepository<EntityUser>
 *
 * @method EntityUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method EntityUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method EntityUser[]    findAll()
 * @method EntityUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry,
        private UserSecurityInfoRepository $userSecurityInfoRepository,
        private AddressRepository $addressRepository,
        private EmailRepository $emailRepository,
        private DocumentTypeRepository $documentTypeRepository
    )
    {
        parent::__construct($registry, EntityUser::class);
    }

    public function isEmailAlreadyRegistered(User | string $emailOrUser): bool
    {
        if($emailOrUser instanceof User){
            $email = $emailOrUser->email()->address;
        }

        if(is_string($emailOrUser)){
            $email = $emailOrUser;
        }

        return $this->emailRepository->findBy(['address' => $email]) || $this->userSecurityInfoRepository->findBy(['email' => $email]);
    }

    public function isDocumentAlreadyRegistered(User $user): bool
    {
        return false;
    }

    public function registerNewUser(User $user, string $hashedPassword): void
    {
        /**
         * Security information from security bundle for authentication
         */
        $securityUser = new UserSecurityInfo();
        $securityUser->setEmail($user->email()->address);
        $securityUser->setPassword($hashedPassword);

        $entityUser = $this->createFromDomainUser($user);
        $entityUser->setSecurityInfo($securityUser);

        $this->getEntityManager()->persist($entityUser);
        $this->getEntityManager()->flush();
    }

    public function createFromDomainUser(User $domainUser)
    {
        $entityUser = new EntityUser();
        
        $entityUser->setFirstName($domainUser->firstName());
        $entityUser->setLastName($domainUser->lastName());
        $entityUser->setBirthDate(new DateTimeImmutable($domainUser->birthDate()));
        $entityUser->setDocument($domainUser->document()->number());
        $entityUser->setEmail($this->emailRepository->createFromDomainEmail($domainUser->email()));
        $entityUser->setDocumentType($this->documentTypeRepository->findOneBy(
            ["type" => strtoupper($domainUser->document()->type())]
        ));

        if(!is_null($domainUser->homeAddress())){
            $address = $this->addressRepository->createAddressFromDomainAddress($domainUser->homeAddress());
            $entityUser->setAddress($address);
        }

        return $entityUser;
    }


//    /**
//     * @return User[] Returns an array of User objects
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

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
