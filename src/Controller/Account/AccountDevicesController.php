<?php
namespace App\Controller\Account;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Application\Account\RegisterAccount\RegisterAccountService;

class AccountDevicesController extends AbstractController
{
    #[Route('/account/device', name: 'link_device', methods: ['POST'])]
    public function registerAccount(
        Request $request, 
        RegisterAccountService $registerAccountService
    ): JsonResponse
    {
        

        return $this->json([
            'message' => 'Linked new device successfully.'
        ]);
    }
}
