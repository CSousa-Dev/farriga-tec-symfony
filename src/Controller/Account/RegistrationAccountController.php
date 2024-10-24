<?php

namespace App\Controller\Account;

use DocsMaker\Attributes\ApiResource;
use App\Application\Account\DTOs\AddressDTO;
use App\Application\Account\DTOs\DocumentDTO;
use DocsMaker\Attributes\Schema\Properties\PropObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Application\Account\DTOs\RegisterAccountDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Application\Account\RegisterAccount\RegisterAccountService;
use DocsMaker\Attributes\Method;
use DocsMaker\Attributes\RequestBody;
use DocsMaker\Attributes\ResourcePath;
use DocsMaker\Attributes\Response;

#[ApiResource(
    name: 'Account',
    description: 'This resource is responsible for account management. Possibility to: register a new account, change password, update account information, etc.',
)]
class RegistrationAccountController extends AbstractController
{
    #[ResourcePath(
        name: '/account', 
        description: 'This is the first step to create a new account, here you will register the account information.',
        sumary: 'Register a new account'
    )]
    #[Method('POST')]
    #[RequestBody(
        contentType: 'application/json',
        description: 'The account information to be registered.',
        content: new PropObject(ref: RegisterAccountDTO::class)
    )]
    #[Response(
        statusCode: 200,
        description: 'Account registered successfully.'
    )]
    #[Route('/account', name: 'account', methods: ['POST'])]
    public function registerAccount(
        Request $request, 
        RegisterAccountService $registerAccountService
    ): JsonResponse
    {
        $json = $request->getContent();
        $payload = json_decode($json);


        if(is_null($payload))
        {
            return $this->json(
                data: [
                    'message' => 'Payload is empty, please check the request body and try again.'
                ], 
                status: 422
            );
        }

        $address = isset($payload->address) ? new AddressDTO(
            street:         $this->ifIssetGet($payload->address, 'street'),
            number:         $this->ifIssetGet($payload->address, 'number'),
            neighborhood:   $this->ifIssetGet($payload->address, 'neighborhood'),
            city:           $this->ifIssetGet($payload->address, 'city'),
            state:          $this->ifIssetGet($payload->address, 'state'),
            country:        $this->ifIssetGet($payload->address, 'country'),
            zipCode:        $this->ifIssetGet($payload->address, 'zipCode'),
            complement:     $this->ifIssetGet($payload->address, 'complement'),
            reference:      $this->ifIssetGet($payload->address, 'reference')
        
        ) : null;

        $document = isset($payload->document) ? new DocumentDTO(
            number:     $this->ifIssetGet($payload->document, 'number'),
            type:       $this->ifIssetGet($payload->document, 'type')
        ) : new DocumentDTO(null, null);

        $registerAccountDto = new RegisterAccountDTO(
            firstName:      $this->ifIssetGet($payload->firstName),
            lastName:       $this->ifIssetGet($payload->lastName),
            email:          $this->ifIssetGet($payload->email),
            plainPassword:  $this->ifIssetGet($payload->plainPassword),
            birthDate:      $this->ifIssetGet($payload->birthDate),
            document:       $document,
            addressDto:     $address
        );

        $registerAccountService->execute($registerAccountDto);

        return $this->json([
            'message' => 'Registered account successfully.'
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
