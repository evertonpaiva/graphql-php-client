<?php

namespace GraphqlClient\Exception;

/**
 * Class HeaderNotDefinedException
 * Exceção para cabeçalho não definido
 *
 * @package GraphqlClient\Exception
 */
class HeaderNotDefinedException extends \Exception {
    /**
     * Mensagem de erro para cabeçalho não fornecido
     */
    const MSG_EMPTY_HEADER = 'Recupere o cabeçalho da sessão e passe-o ao construtor.';

    public function __construct($headerType)
    {
        $message = 'Cabeçalho '.$headerType.' não definido. '.self::MSG_EMPTY_HEADER;
        parent::__construct($message);
    }
}
