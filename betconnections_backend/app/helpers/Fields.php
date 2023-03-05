<?php

namespace App\Helpers;

class Fields
{
    public static function validate(array $fields)
    {
        $allFieldsAreOk = true;
        foreach ($fields as $field) {
            if (!$field) {
                $allFieldsAreOk = false;
            }
        }
        return $allFieldsAreOk;
    }

    public static function messageError($response)
    {
        $responseMessage = json_encode([
            'error'     => true,
            'message'   => 'Todos los atributos son requeridos.'
        ]);
        $response->getBody()->write($responseMessage);
        return $response->withHeader('Content-type', 'application/json')->withStatus(400);
    }
}
