<?php
namespace App;

use App\Repository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class Controller
{
  private Repository $repository;

  public function __construct()
  {
    $this->repository = new Repository();
  }

  public function __invoke(Request $request, Response $response, array $args): Response
  {    
    // $params = $request->getQueryParams();
    // if(! isset($params["lat"])) {
    //   return $this->jsonResponse($response, 'error', 'The field "Latitude" is required.', 500);
    // }

    // if(! isset($params["lng"])) {
    //   return $this->jsonResponse($response, 'error', 'The field "Longitude" is required.', 500);
    // }

    $data = (array) $request->getParsedBody();
    $data = json_decode(json_encode($data), false);

    if(! isset($data->device)) {
      return $this->jsonResponse($response, 'error', 'The field "Devide Id" is required.', 500);
    }

    if(! isset($data->latitude)) {
      return $this->jsonResponse($response, 'error', 'The field "Latitude" is required.', 500);
    }

    if(! isset($data->longitude)) {
      return $this->jsonResponse($response, 'error', 'The field "Longitude" is required.', 500);
    }

    $data = array(
      'device'    => $data->device ?? 0,
      'latitude'  => $data->latitude ?? 0,
      'longitude' => $data->longitude ?? 0,
      'altitude'  => $data->altitude ?? 0,
      'speed'     => $data->speed ?? 0,
      'accuracy'  => $data->accuracy ?? 0,
      'provider'  => $data->provider ?? NULL,
      'time'      => $data->time ?? 0
    );

    $result = $this->repository->addLocation($data);


    return $this->jsonResponse($response, 'success', $data, 200);
  }

  function jsonResponse(Response $response, string $status, $message, int $code): Response 
  {
    $result = [
      'code'   => $code,
      'status' => $status,
      'data'   => $message
    ];

    $response->getBody()->write(json_encode($result));
    return $response
      ->withHeader('content-type', 'application/json')
      ->withStatus($code);
  }

}