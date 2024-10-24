<?php 
namespace App\Controller\Device;

use App\Infra\MQTTEventManager;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IrrigationEventController extends AbstractController
{

    #[Route('/irrigation/events/{userId}', name: 'irrigation_events', methods: ['GET'])]
    public function deviceEvents($userId): StreamedResponse
    {
        set_time_limit(0);
        $response = new StreamedResponse();        
        
        // $response->setCallback(function () use ($userId): void {                        
            
        //     $eventManager = new MQTTEventManager();

        //     echo 'Configurando conexão';
        //     ob_flush();
        //     flush();
        //     $eventManager->configClient();

        //     echo 'Connectando';
        //     ob_flush();
        //     flush();
        //     $eventManager->connect();


        //     echo 'Aguardando medições';
        //     ob_flush();
        //     flush();
        //     $this->listenEventsAllDeviceEvents->execute($userId);


        //     $eventManager->client()->registerLoopEventHandler(function () {
        //         if(connection_aborted()) {
        //             echo 'Abortando';
        //             ob_flush();
        //             flush();
        //             exit();
        //         }
                
        //         echo 'Loop';
        //         ob_flush();
        //         flush();
        //         sleep(2);
        //     });

        //     $eventManager->client()->loop();
        // });

        // $response->headers->set('Cache-Control', 'no-cache');
        // $response->headers->set('Content-Type', 'text/event-stream');
        
        return $response;
    }
}
