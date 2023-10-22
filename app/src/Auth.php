<?php
namespace App;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class Auth
{

  public function __construct()
  {

  }

  public function __invoke(Request $request, RequestHandler $handler): Response
  {
    $authHeader = $request->getHeaderLine('Authorization');
    if (! $authHeader) {
      throw new \Exception('Token required.', 401);
    }

    $token = explode('Bearer ', $authHeader);
    if (! isset($token[1])) {
      throw new \Exception('Token invalid.', 401);
    }

    $this->checkToken($token[1]);
    $response = $handler->handle($request);
    
    return $response;
  }

  private function checkToken(string $token): void
  {
    if($token != "test") {
      throw new \Exception('You are not authorized.', 401);
    }
  }
}
