<?php 
namespace App\Domain\Devices;

interface IEventManager
{
    public function listen(string $event, callable $callback): void;
    public function notify(string $event, string $data): void;
}