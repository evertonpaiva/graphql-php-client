<?php

namespace GraphqlClient\GraphqlQuery;

use GraphQL\Query;

class SingleRelationQuery extends Query
{

    public function __construct($relationName, $fields)
    {
        parent::__construct($relationName);

        self::setSelectionSet($fields);
    }
}
