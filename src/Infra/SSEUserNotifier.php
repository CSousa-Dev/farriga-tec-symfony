<?php 
namespace App\Infra;

use App\Domain\Devices\IUserNotifier;

class SSEUserNotifier implements IUserNotifier
{
    public function send(string $event, array $data): void
    {
        $json = json_encode(["event" => $event, "data" => $data, "time" => time()]);
        echo $json;
        ob_flush();
        flush();
    }   
}