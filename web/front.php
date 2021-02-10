<?php

// framework/front.php
require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;


function render_template($request)
{
    extract($request->attributes->all(), EXTR_SKIP);
    ob_start();
    include sprintf(__DIR__.'/../src/pages/%s.php', $_route);

    return new Response(ob_get_clean());
}


$request = Request::createFromGlobals();
$routes = include __DIR__.'/../src/app.php';
$context = new Routing\RequestContext();
$context->fromRequest($request);
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);


//try {
//    extract($matcher->match($request->getPathInfo()), EXTR_SKIP);
//    ob_start();
//    include sprintf(__DIR__.'/../src/pages/%s.php', $_route);
//
//    $response = new Response(ob_get_clean());
//} catch (Routing\Exception\ResourceNotFoundException $exception) {
//    $response = new Response('Not Found', 404);
//} catch (Exception $exception) {
//    $response = new Response('An error occurred', 500);
//}


$routes->add('hello', new Routing\Route('/hello/{name}', [
    'name' => 'World',
    '_controller' => 'render_template',
]));


try {
    $request->attributes->add($matcher->match($request->getPathInfo()));
    $response = call_user_func('render_template', $request);
} catch (Routing\Exception\ResourceNotFoundException $exception) {
    $response = new Response('Not Found', 404);
} catch (Exception $exception) {
    $response = new Response('An error occurred', 500);
}
//$map = [
//    '/hello' => 'hello',
//    '/bye'   => 'bye',
//];
//
//
//$path = $request->getPathInfo();
//if (isset($map[$path])) {
//    ob_start();
//    extract($request->query->all(), EXTR_SKIP);
//
//    include sprintf(__DIR__.'/../src/pages/%s.php', $map[$path]);
//    $response = new Response(ob_get_clean());
//
//} else {
//    $response = new Response('Not Found', 404);
//
//}

$response->send();
