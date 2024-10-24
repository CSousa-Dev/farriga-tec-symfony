<?php 
namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use App\Application\NotConfirmedEmailException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class NotConfirmedEmailExceptionistener
{
    public function __invoke(ExceptionEvent $event): void
    {
        if(!$event->getThrowable() instanceof NotConfirmedEmailException)
        {
            return;
        }
    
        /**
         * @var NotConfirmedEmailException
         */
        $exception = $event->getThrowable();

        $data = [
            'code' => $exception->getCode(),
            'message' => $exception->getMessage(),
            'token' => $exception->authToken()
        ];
            
        $response = new JsonResponse($data, 40101);
        $event->setResponse($response);
    }
}