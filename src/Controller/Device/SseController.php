<?php

namespace App\Controller\Device;

use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SseController extends AbstractController
{
    #[Route('/test', name: 'device_monitoring', methods: ['GET'])]
    public function index(
        LoggerInterface $logger
    )
    {
        $response = new StreamedResponse();
        $response->setCallback(function (): void {
            while (true) {
                echo 'data: ' . json_encode(['message' => 'Hello World']) . PHP_EOL;
                echo PHP_EOL;
                flush();
                sleep(2);
            }
        });
        $response->send();
    }
}
