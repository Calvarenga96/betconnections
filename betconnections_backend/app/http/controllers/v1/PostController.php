<?php

namespace App\Http\Controllers\V1;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Helpers\CurlRequest;
use App\Helpers\Fields;

class PostController
{
    private static $URL = 'https://jsonplaceholder.typicode.com';

    public static function index(Request $request, Response $response)
    {
        $page   = isset($request->getQueryParams()['page']) ? (int) $request->getQueryParams()['page'] : 1;
        $length = 15;
        $offset = ($page - 1) * $length;

        $curlRequest            = new CurlRequest(self::$URL);
        $content                = json_decode($curlRequest->get('/posts'));
        $contentWithPaginator   = array_slice($content, $offset, $length);
        $contentToJson          = json_encode($contentWithPaginator);

        $response->getBody()->write($contentToJson);

        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function store(Request $request, Response $response)
    {
        $title  = isset($request->getParsedBody()['title'])     ? $request->getParsedBody()['title']    : null;
        $body   = isset($request->getParsedBody()['body'])      ? $request->getParsedBody()['body']     : null;
        $userId = isset($request->getParsedBody()['userId'])    ? $request->getParsedBody()['userId']   : null;

        $fieldsAreCorrect = Fields::validate([$title, $body, $userId]);

        if (!$fieldsAreCorrect) {
            return Fields::messageError($response);
        }

        $request = new CurlRequest(self::$URL);
        $request->setBody([
            'title'     => $title,
            'body'      => $body,
            'userId'    => $userId
        ]);
        $request->post('/posts');

        return $response->withHeader('Content-type', 'application/json')->withStatus(201);
    }

    public static function show(Request $request, Response $response, array $args)
    {
        $userId         = $args['id'];
        $curlRequest    = new CurlRequest(self::$URL);
        $content        = $curlRequest->get('/posts/' . $userId);

        $response->getBody()->write($content);

        return $response->withHeader('Content-type', 'application/json');
    }

    public static function update(Request $request, Response $response, array $args)
    {
        $idArgs = $args['id'];
        $id     = isset($request->getParsedBody()['id'])        ? $request->getParsedBody()['id']       : null;
        $title  = isset($request->getParsedBody()['title'])     ? $request->getParsedBody()['title']    : null;
        $body   = isset($request->getParsedBody()['body'])      ? $request->getParsedBody()['body']     : null;
        $userId = isset($request->getParsedBody()['userId'])    ? $request->getParsedBody()['userId']   : null;

        $fieldsAreCorrect = Fields::validate([$id, $title, $body, $userId]);

        if (!$fieldsAreCorrect) {
            return Fields::messageError($response);
        }

        $curlRequest = new CurlRequest(self::$URL);
        $curlRequest->setBody([
            'id'        => $id,
            'title'     => $title,
            'body'      => $body,
            'userId'    => $userId
        ]);
        $curlRequest->put('/posts/' . $idArgs);

        $contentRespose = [
            'message' => 'Usuario modificado correctamente.'
        ];
        $contentResposeToJson = json_encode($contentRespose);

        $response->getBody()->write($contentResposeToJson);

        return $response->withHeader('Content-type', 'application/json')->withStatus(200);
    }

    public static function destroy(Request $request, Response $response, array $args)
    {
        $id = $args['id'];

        $curlRequest = new CurlRequest(self::$URL);
        $curlRequest->delete('/posts/' . $id);

        $contentRespose = [
            'message' => 'Post eliminado exitosamente'
        ];
        $contentResposeToJson = json_encode($contentRespose);

        $response->getBody()->write($contentResposeToJson);

        return $response->withHeader('Content-type', 'application/json')->withStatus(200);
    }
}
