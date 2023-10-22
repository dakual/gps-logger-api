<?php
namespace App;

use App\Repository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class PositionController
{
  private Repository $repository;

  public function __construct()
  {
    $this->repository = new Repository();
  }

  public function __invoke(Request $request, Response $response, array $args): Response
  {
    $data = (array) $request->getParsedBody();
    if (empty($data) || !isset($data['device'])) {
      throw new \Exception(
        'Request failed: Device Id is required!', 400
      );
    }
    $device   = $data["device"];
    $time     = $data["time"] ?? 1;
    $accuracy = $data["accuracy"] ?? 25;
    $provider = $data["provider"] ?? "network";

    $result = $this->repository->getMap($device, $accuracy, $time, $provider);

    return $this->jsonResponse($response, 'success', $result, 200);
  }

  function jsonResponse(Response $response, string $status, array $message, int $code): Response 
  {
    $result = [
      'code'   => $code,
      'status' => $status,
      'data'   => $message
    ];

    $response->getBody()->write(json_encode($result, JSON_NUMERIC_CHECK));
    return $response
      ->withHeader('content-type', 'application/json')
      ->withStatus($code);
  }

}