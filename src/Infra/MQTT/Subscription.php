<?php 
namespace App\Infra\MQTT;

class Subscription
{
    private $callback;

    public function __construct(
        private string $topic,
        callable $callback
    )
    {
        $this->callback = $callback;
    }

    public function getTopic(): string
    {
        return $this->topic;
    }

    public function getCallback(): callable
    {
        return $this->callback;
    }
}