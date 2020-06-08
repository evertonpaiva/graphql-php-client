<?php

namespace GraphqlClient\Exception;

/**
 * Class ExpiredTokenException
 * Exceção para token expirado
 *
 * @package GraphqlClient\Exception
 */
class ExpiredTokenException extends \Exception {
    public function __construct($tokenType)
    {
        $message = 'O token '.$tokenType.' já está expirado. Efetue login novamente';
        parent::__construct($message);
    }
}
