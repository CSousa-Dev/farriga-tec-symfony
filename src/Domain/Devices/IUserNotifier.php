<?php 
namespace App\Domain\Devices;

interface IUserNotifier
{
    public function send(string $event, array $data): void;
}