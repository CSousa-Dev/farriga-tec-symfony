<?php 

namespace App\EventListener;

use App\Application\InvalidInputException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
class InvalidInputExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        if(!$event->getThrowable() instanceof InvalidInputException)
        {
            return;
        }
    
        /**
         * @var InvalidInputException
         */
        $exception = $event->getThrowable();

        $data = [
            'code' => $exception->getCode(),
            'message' => $exception->getMessage(),
            'errors' => $exception->validationResult()->errors(),
            'validatedFields' => $exception->validationResult()->validatedFields()
        ];
            
        $response = new JsonResponse($data, Response::HTTP_UNPROCESSABLE_ENTITY);
        $event->setResponse($response);

    }
}