<?php 

namespace App\Security;

use App\Application\NotConfirmedEmailException;
use App\Infra\Doctrine\Entity\Security\ApiToken;
use App\Infra\Doctrine\Repository\Account\UserRepository as AccountUserRepository;
use App\Infra\Doctrine\Repository\Security\ApiTokenRepository;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;

class AccessTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(
        private ApiTokenRepository      $repository,
        private AccountUserRepository   $userRepository
    ) {
    }

    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        // e.g. query the "access token" database to search for this token

        /**
         * @var ApiToken|null $accessToken
         */
        $accessToken = $this->repository->findOneByToken($accessToken);
        
        if (null === $accessToken || !$accessToken->isValid()) {
            throw new BadCredentialsException('Invalid credentials.');
        }

        // $owner = $accessToken->getOwnedBy();

        // $domainUser = $owner->getDomainUser();

        // if($domainUser->getEmail()->getValidatedAt() === null) {
        //     throw new NotConfirmedEmailException(token: $accessToken->getToken());
        // }
        
        // and return a UserBadge object containing the user identifier from the found token
        // (this is the same identifier used in Security configuration; it can be an email,
        // a UUUID, a username, a database ID, etc.)
        return new UserBadge($accessToken->getOwnedBy()->getUserIdentifier());
    }
}