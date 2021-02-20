<?php
// example.com/web/front.php
require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;

$request = Request::createFromGlobals();
$routes = include __DIR__.'/../src/app.php';
$container = include __DIR__.'/../src/container.php';


//$framework = new Simplex\Framework($routes);
$response = $container->get('framework')->handle($request);


//$framework->handle($request)->send();
$response->send();


