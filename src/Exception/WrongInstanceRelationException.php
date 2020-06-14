<?php

namespace GraphqlClient\Exception;

class WrongInstanceRelationException extends \Exception
{
    public function __construct($relationName, $className)
    {
        $message = 'Erro ao criar relacionamento. Relation '.$relationName.' não é do tipo '.$className;
        parent::__construct($message);
    }
}
