<?php

namespace GraphqlClient\Exception;

/**
 * Class DecodeTokenException
 * Exceção para token não decodificado
 *
 * @package GraphqlClient\Exception
 */
class DecodeTokenException extends \Exception
{
    public function __construct($tokenType, $errorMessage)
    {
        $message = 'Não foi possível decodificar o token '.$tokenType.' Mensagem: '.$errorMessage;
        parent::__construct($message);
    }
}
