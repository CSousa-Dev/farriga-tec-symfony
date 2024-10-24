<?php 
namespace App\Domain\Devices;

class Sensor 
{
    private $measurement;
    private bool $usage = true;
    private $treshold;

    public function __construct(
        public readonly int $id,
        public readonly string $type
    )
    {}

    public function addMeasurement($measurement): void
    {
        $this->measurement = $measurement;
    }

    public function addTreshold($treshold): void
    {
        $this->treshold = $treshold;
    }

    public function treshold()
    {
        return $this->treshold;
    }

    public function measurement()
    {
        return $this->measurement;
    }

    public function usage()
    {
        return $this->usage;
    }

    public function setUsage(bool $usage): void
    {
        $this->usage = $usage;
    }
}