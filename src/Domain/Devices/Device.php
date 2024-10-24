<?php 
namespace App\Domain\Devices;

use App\Domain\Devices\Sensor;
use App\Domain\Devices\Irrigator;
use App\Domain\Devices\SensorList;
use App\Domain\Devices\IrrigatorList;

class Device
{
    private $sensorList;
    private $irrigationState = false;

    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $model,
        public readonly string $macAddress
    )
    {
        $this->sensorList = new SensorList();
    }

    public function addSensor(Sensor $sensor)
    {
        $this->sensorList->add($sensor);
    }

    public function getSensors(): SensorList
    {
        return $this->sensorList;
    }

    public function irrigationState(bool $irrigating)
    {
        $this->irrigationState = $irrigating;
    }
}