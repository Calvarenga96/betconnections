<?php

use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

use App\Controllers\PostController;

require __DIR__ . '/../vendor/autoload.php';

// Instantiate App
$app = AppFactory::create();

// Add Routing Middleware
$app->addRoutingMiddleware();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add routes
$app->group('/v1', function (RouteCollectorProxy $group) {
    $group->get('/posts',           [PostController::class, 'index']);
    $group->get('/posts/{id}',      [PostController::class, 'show']);
    $group->post('/posts',          [PostController::class, 'store']);
    $group->put('/posts/{id}',      [PostController::class, 'update']);
    $group->delete('/posts/{id}',   [PostController::class, 'destroy']);
});

$app->run();
