<?php 
namespace App\Controller\Device;

use App\Domain\Devices\Device;
use App\Domain\Devices\Sensor;
use App\Domain\Devices\Irrigator;
use App\Domain\Devices\MonitoringEvents;
use App\Infra\MQTT\MQTTEventManager;
use App\Infra\SSEUserNotifier;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DeviceMonitoringController extends AbstractController
{
    #[Route('/device/monitoring', name: 'device_monitoring', methods: ['GET'])]
    public function index(
        MonitoringEvents $monitoringEvents,
        SSEUserNotifier $sseUserNotifier
    ): StreamedResponse
    {
        set_time_limit(60);
        $response = new StreamedResponse();

        $device = new Device(
            id: '1',
            name: 'Device 1',
            model: 'Model 1',
            macAddress: 'B0A73228E458'
        );
        

        $sensor = new Sensor(
            id: 1,
            type: 'umidadeDoSolo'
        );

        $device->addSensor($sensor);

        $response->setCallback(function () use ($device, $monitoringEvents, $sseUserNotifier): void {                        
            
            $eventManager = new MQTTEventManager();

            echo 'Configurando conexão';
            ob_flush();
            flush();
            $eventManager->configClient();

            echo 'Connectando';
            ob_flush();
            flush();
            $eventManager->connect();


            echo 'Aguardando medições';
            ob_flush();
            flush();

            $monitoringEvents->config($eventManager);

            /**
             * @var Sensor $sensor
             */
            $monitoringEvents->listenForIrrigatorStateChanges($device);
            
            foreach($device->getSensors()->all() as $sensor) {
                $monitoringEvents->listenForMeasurementSensorChanges($device, $sensor->id);
            }

            $eventManager->mountSubscriptions();
            /**
             * @var MQTTEventManager $eventManager
             */
            $eventManager->client()->registerLoopEventHandler(function () {
                if(connection_aborted()) {
                    echo 'Abortando';
                    ob_flush();
                    flush();
                    exit();
                }

                sleep(1);
            });            

            $eventManager->client()->loop();
        });

        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Content-Type', 'text/event-stream');
        
        return $response;
    }
}