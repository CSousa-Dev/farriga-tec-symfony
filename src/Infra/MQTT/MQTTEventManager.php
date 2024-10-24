<?php
namespace App\Infra\MQTT;

use App\Domain\Devices\IEventManager;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

class MQTTEventManager implements IEventManager
{
    private string $server;
    private int $port;
    private MqttClient $client;
    private string $password;
    private string $user;

    /**
     * @var Subscription[]
     */
    private array $subscriptions;

    public function __construct()
    {
        $this->server     = $_ENV["MQTT_SERVER"];
        $this->port       = $_ENV["MQTT_PORT"];      
        $this->user       = $_ENV["MQTT_USER"];
        $this->password   = $_ENV["MQTT_PASSWORD"]; 
    }

    public function configClient($clientId = null)
    {
        $this->client = new MqttClient($this->server, $this->port, $clientId);
    }

    public function connect()
    {
        $connectionSettings = (new ConnectionSettings)
                    ->setUsername($this->user)
                    ->setPassword($this->password)
                    ->setUseTls(true)
                    ->setTlsSelfSignedAllowed(true) // Allow self-signed certificates. Discouraged for production use.
                    ->setTlsVerifyPeer(false);

        // Conecta ao broker MQTT
        $this->client->connect($connectionSettings);
    }

    public function listen(string $event, callable $callback): void
    {
        $this->subscriptions[] = new Subscription($event, $callback);
    }

    public function notify(string $event, $data): void
    {
        $this->client->publish(
            $event,
            $data
        );
    }

    public function client()
    {
        return $this->client;
    }

    public function mountSubscriptions(): MqttClient
    {
        foreach($this->subscriptions as $subscription)
        {
            $this->client->subscribe($subscription->getTopic(), $subscription->getCallback());
        }

        return $this->client;
    }
}