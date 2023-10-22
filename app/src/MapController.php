<?php
namespace App;

use App\Repository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class MapController
{
  private Repository $repository;
  private $api_url;
  private $default_device;
  private $default_accuracy;
  private $default_provider;
  private $default_time;

  public function __construct()
  {
    $this->repository = new Repository();

    $this->api_url          = $_ENV['API_URL'];
    $this->default_device   = $_ENV['DEFAULT_DEVICE'];
    $this->default_accuracy = $_ENV['DEFAULT_ACCURACY'];
    $this->default_provider = $_ENV['DEFAULT_PROVIDER'];
    $this->default_time     = $_ENV['DEFAULT_TIME'];
  }

  public function __invoke(Request $request, Response $response, array $args): Response
  {
    $devices = $this->repository->getDevices();
    $vars = [
      "api_url"  => $this->api_url,
      "devices"  => $devices, 
      "time"     => [1,2,4,6,8,10,12,24,48,72],
      "accuracy" => [5,10,15,20,25,30,35,40,45,50,100],
      "provider" => ["network", "gps"],
      "default"  => [
        "device"    => $this->default_device,
        "time"      => $this->default_time,
        "accuracy"  => $this->default_accuracy,
        "provider"  => $this->default_provider
      ]
    ];

    $renderer = new PhpRenderer(__DIR__ . '/views/', $vars);
    $renderer->render($response, 'map.php', $args);

    return $response;
  }
}