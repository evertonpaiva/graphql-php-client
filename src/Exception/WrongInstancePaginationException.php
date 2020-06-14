<?php

namespace GraphqlClient\Exception;

class WrongInstancePaginationException extends \Exception
{
    public function __construct($className, $relationName = null)
    {
        $message = 'Erro ao criar paginação para classe '.$className;

        if (!is_null($relationName)) {
            $message .= 'Paginação do relacionamento '.$relationName.' não é da classe de paginações.';
        }
        parent::__construct($message);
    }
}
