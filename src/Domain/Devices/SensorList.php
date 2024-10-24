<?php 
namespace App\Domain\Devices;

use App\Domain\Devices\Sensor;

class SensorList
{
    private $list = [];

    public function add(Sensor $sensor)
    {
        $this->list[$sensor->id] = $sensor;
    }

    public function remove(Sensor $sensor)
    {
        unset($this->list[$sensor->id]);
    }

    public function get($id): Sensor
    {
        return $this->list[$id];
    }

    public function all(): array
    {
        return $this->list;
    }
}