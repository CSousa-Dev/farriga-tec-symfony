<?php 

namespace App\EventListener\RegisterAccount;

use App\Application\InvalidInputException;
use App\Domain\Account\Services\RegisterUser\UserEmailAlreadyRegisteredException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class UserEmailAlreadyRegisteredExceptionListener 
{
    public function __invoke(ExceptionEvent $event): void
    {
        if(!$event->getThrowable() instanceof UserEmailAlreadyRegisteredException)
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
        ];
            
        $response = new JsonResponse($data, Response::HTTP_UNPROCESSABLE_ENTITY);
        $event->setResponse($response);
    }
}