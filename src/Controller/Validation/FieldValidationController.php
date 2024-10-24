<?php 
namespace App\Controller\Validation;

use App\Application\Account\ValidationServices\FieldByField\FieldByFieldValidationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FieldValidationController extends AbstractController
{
    #[Route('/validation/{service}', name: 'validate-fields', methods: ['POST'])]
    public function validateFieldsFor(FieldByFieldValidationService $fieldByFieldValidationService, Request $request, $service)
    {
        $payload = json_decode($request->getContent(), true);

        if(!isset($payload['fields']) || empty($payload['fields'])) {
            return $this->json([
                'message' => 'The fields key is required in the payload. Nothing to validate.',
            ], 404);
        }

        $fields = $payload['fields'];
        $validationResult = $fieldByFieldValidationService->execute($service, $fields);

        return $this->json([
            'message' => 'Validation done.',
            'errors' => $validationResult->errors(),
            'validatedFields' => $validationResult->validatedFields()
        ]);
    }
}