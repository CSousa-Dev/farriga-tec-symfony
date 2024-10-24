<?php

namespace App\Controller\Account;

use App\Application\Account\DTOs\AddressDTO;
use App\Application\Account\DTOs\DocumentDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Application\Account\DTOs\RegisterAccountDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Application\Account\RegisterAccount\RegisterAccountService;

class ChangeUserPasswordController extends AbstractController
{
    #[Route('/account/password', name: 'account', methods: ['POST'])]
    public function registerAccount(
        Request $request, 
        RegisterAccountService $registerAccountService
    ): JsonResponse
    {
        $json = $request->getContent();
        $payload = json_decode($json);
        $address = isset($payload->address) ? new AddressDTO(
            $this->ifIssetGet($payload->address, 'street'),
            $this->ifIssetGet($payload->address, 'number'),
            $this->ifIssetGet($payload->address, 'complement'),
            $this->ifIssetGet($payload->address, 'reference'),
            $this->ifIssetGet($payload->address, 'neighborhood'),
            $this->ifIssetGet($payload->address, 'city'),
            $this->ifIssetGet($payload->address, 'state'),
            $this->ifIssetGet($payload->address, 'country'),
            $this->ifIssetGet($payload->address, 'zipCode')
        
        ) : null;

        $document = isset($payload->document) ? new DocumentDTO(
            $this->ifIssetGet($payload->document, 'number'),
            $this->ifIssetGet($payload->document, 'type')
        ) : new DocumentDTO(null, null);

        $registerAccountDto = new RegisterAccountDTO(
            $this->ifIssetGet($payload->firstName),
            $this->ifIssetGet($payload->lastName),
            $this->ifIssetGet($payload->email),
            $this->ifIssetGet($payload->plainPassword),
            $this->ifIssetGet($payload->birthDate),
            $document,
            $address
        );

        $registerAccountService->execute($registerAccountDto);

        return $this->json([
            'message' => 'Registro de uma nova conta',
            'path' => 'src/Controller/AccountController.php',
        ]);
    }

    private function ifIssetGet($value, string $field = null): ?string
    {
        if(isset($field))
        {
            return isset($value->$field) ? $value->$field : null;
        }

        return isset($value) ? $value : null;
    }
}
