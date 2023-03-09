<?php

namespace App\Http\Controllers\V1;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Http\Models\User;

use App\Helpers\Fields;

class UserController
{
    public static function index(Request $request, Response $response)
    {
        $user           = new User;
        $users          = $user->findAll();
        $usersToJson    = json_encode($users);

        $response->getBody()->write($usersToJson);

        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function store(Request $request, Response $response)
    {
        $data = $request->getParsedBody();

        // Validar datos
        $fieldsAreCorrect = Fields::validate([$data['name'], $data['email'], $data['password']]);
        if (!$fieldsAreCorrect) return Fields::messageError($response);

        // Crear nuevo usuario
        $user = new User;
        $user->create($data['name'], $data['email'], $data['password']);

        // Responder con el nuevo usuario creado
        $userToJson = json_encode($data);
        $response->getBody()->write($userToJson);

        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    public static function show(Request $request, Response $response, array $args)
    {
        $userId = $args['id'];
        $user   = new User;
        $user   = $user->findById($userId);

        if (!$user) {
            $response->getBody()->write(json_encode(['error' => true, 'message' => 'Usuario no encontrado']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        $userToJson = json_encode($user);
        $response->getBody()->write($userToJson);

        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function update(Request $request, Response $response, array $args)
    {
        $userId     = $args['id'];
        $user       = new User;
        $userById   = $user->findById($userId);

        if (!$userById) {
            $response->getBody()->write(json_encode(['error' => true, 'message' => 'Usuario no encontrado']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        $data = $request->getParsedBody();

        // Validar datos
        $fieldsAreCorrect = Fields::validate([$data['name'], $data['email'], $data['password']]);
        if (!$fieldsAreCorrect) return Fields::messageError($response);

        // Actualizar datos del usuario
        $user->update($userId, $data['name'], $data['email'], $data['password']);

        // Responder con el usuario actualizado
        $userToJson = json_encode($data);
        $response->getBody()->write($userToJson);

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public static function destroy(Request $request, Response $response, array $args)
    {
        $userId     = $args['id'];
        $user       = new User;
        $userById   = $user->findById($userId);

        if (!$userById) {
            $response->getBody()->write(json_encode(['error' => true, 'message' => 'Usuario no encontrado']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        // Eliminar usuario
        $user->delete($userId);

        $response->getBody()->write(json_encode(['success' => true, 'message' => 'Usuario eliminado']));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}
