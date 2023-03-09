<?php

use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

use App\Http\Controllers\V1\PostController;
use App\Http\Controllers\V1\UserController;

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

    $group->get('/users',           [UserController::class, 'index']);
    $group->get('/users/{id}',      [UserController::class, 'show']);
    $group->post('/users',          [UserController::class, 'store']);
    $group->put('/users/{id}',      [UserController::class, 'update']);
    $group->delete('/users/{id}',   [UserController::class, 'destroy']);
});

$app->run();
