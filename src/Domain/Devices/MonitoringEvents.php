<?php 
namespace App\Domain\Devices;

use App\Domain\Devices\IEventManager;
use App\Domain\Devices\IUserNotifier;

class MonitoringEvents
{
    public function __construct(
        private IUserNotifier $userNotifier
    ){}

    private $eventManager;
    public function config(IEventManager $eventManager)
    {
        $this->eventManager = $eventManager;
    }

    public function listenForIrrigatorStateChanges(
        Device $device
    )
    {   
        $this->eventManager->listen(
            "$device->macAddress/irrigando",
            function($topic, $message) use ($device) {
                $this->userNotifier->send(
                    "irrigator_state_changed",
                    [
                        "device_mac_address" => $device->macAddress,
                        "state"              => $message
                    ]
                );
            }
        );
    }

    public function listenForMeasurementSensorChanges(
        Device $device,
        int $sensorId
    )
    {   
        $type = $device->getSensors()->get($sensorId)->type;
        $this->eventManager->listen(
            "$device->macAddress/$type",
            function($topic, $message) use ($device, $sensorId) {

                if($device->getSensors()->get($sensorId)->measurement() == $message) return;

                $this->userNotifier->send(
                    "sensor_measurement_changed",
                    [
                        "device_mac_address" => $device->macAddress,
                        "sensor_id"          => $sensorId,
                        "measure"            => $message
                    ]
                );

                $device->getSensors()->get($sensorId)->addMeasurement($message);
            }
        );
    }   

    public function eventManager(): IEventManager
    {
        return $this->eventManager;
    }
}