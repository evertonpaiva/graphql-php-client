<?php

namespace GraphqlClient\Exception;

/**
 * Class AuthNotDefinedException
 *
 * Exceção para tipo de autenticação não definida
 *
 * @package GraphqlClient\Exception
 */
class AuthNotDefinedException extends \Exception {
    public function __construct()
    {
        $message = 'Defina o tipo de autenticação para sua requisição graphql.';
        parent::__construct($message);
    }
}
