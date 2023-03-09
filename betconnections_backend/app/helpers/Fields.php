<?php

namespace App\Helpers;

use Psr\Http\Message\ResponseInterface as Response;

class Fields
{
    public static function validate(array $fields): bool
    {
        $allFieldsAreOk = true;
        foreach ($fields as $field) {
            if (!$field || empty($field)) {
                $allFieldsAreOk = false;
            }
        }
        return $allFieldsAreOk;
    }

    public static function messageError(Response $response): Response
    {
        $responseMessage = json_encode([
            'error'     => true,
            'message'   => 'Todos los atributos son requeridos.'
        ]);
        $response->getBody()->write($responseMessage);
        return $response->withHeader('Content-type', 'application/json')->withStatus(400);
    }
}
