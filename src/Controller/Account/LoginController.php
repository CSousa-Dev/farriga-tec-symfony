<?php 
namespace App\Controller\Account;

use App\Application\NotConfirmedEmailException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Infra\Doctrine\Entity\Security\ApiToken;
use App\Infra\Doctrine\Entity\Security\UserSecurityInfo;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Infra\Doctrine\Repository\Security\UserSecurityInfoRepository;

class LoginController extends AbstractController
{
    #[Route('account/login', name: 'login', methods: ['POST'])]
    public function index(#[CurrentUser] UserSecurityInfo $userSecurityInfo, UserSecurityInfoRepository $userSecurityInfoRepository): Response
    {
        if (null === $userSecurityInfo) {
            return $this->json([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = new ApiToken(); 
        $userWithToken = $userSecurityInfoRepository->addApiToken($userSecurityInfo, $token);

        $domainUser = $userWithToken->getDomainUser();

        // if($domainUser->getEmail()->getValidatedAt() === null) {
        //     throw new NotConfirmedEmailException(token: $token->getToken());
        // }

        return $this->json([
            'user'  => $userWithToken->getUserIdentifier(),
            'token' => $token->getToken(),
        ]);

    }
}