<?php

use Dotenv\Dotenv;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selective\BasePath\BasePathMiddleware;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Slim\Views\PhpRenderer;
use App\Controller;
use App\PositionController;
use App\MapController;
use App\ErrorHandler;

require_once('../vendor/autoload.php');

$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->safeLoad();

$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->add(new BasePathMiddleware($app));


$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorHandler    = new ErrorHandler($app->getCallableResolver(), $app->getResponseFactory());
$errorMiddleware->setDefaultErrorHandler($errorHandler);

$app->options("[{routes.*}]", function(Request $req, Response $res, array $args) :Response { 
  return $res; 
});

$app->add(function ($request, $handler) {
  $response = $handler->handle($request);
  return $response
          ->withHeader('Access-Control-Allow-Origin', '*')
          ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
          ->withHeader('Access-Control-Allow-Credentials', 'true')
          ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

$app->get('/', function (Request $request, Response $response) {
  $response->getBody()->write('API v1.0');
  return $response;
});

$app->post('/api', Controller::class)->add(new App\Auth());
$app->post('/positions', PositionController::class)->add(new App\Auth());
$app->get('/api', function (Request $request, Response $response) {
  $response->getBody()->write('Method not allowed!');
  return $response;
});

$app->get('/map', MapController::class);

// $app->get('/map', function ($request, $response, $args) {
//   $vars = [
//     "title" => "Hello - My App"
//   ];
//   $renderer = new PhpRenderer(__DIR__ . '/templates', $vars);
//   return $renderer->render($response, "map.php", $args);
// })->setName('map');



// function circle_distance($lat1, $lon1, $lat2, $lon2) {
//   $rad = M_PI / 180;
//   return acos(sin($lat2*$rad) * sin($lat1*$rad) + cos($lat2*$rad) * cos($lat1*$rad) * cos($lon2*$rad - $lon1*$rad)) * 6378137;
// }

// $lat1 = '36.52706115606389';
// $lon1 = '32.05789689893363';

// $lat2 = '36.52638142254303';
// $lon2 = '32.05670414409723';
// echo circle_distance($lat1, $lon1, $lat2, $lon2);


$app->run();