<?php

namespace GraphqlClient\GraphqlRequest;

use GraphQL\Query;

/**
 * Class PaginatedDataQuery
 * Query GraphQL para os campos da estrutura de dados do padrão Relay
 *
 * edges {
 *   node {
 *      [campo1, campo2, ..., campoN]
 *   }
 * }
 *
 * @package GraphqlClient\GraphqlRequest
 */
class PaginatedDataQuery extends Query
{

    public function __construct($fields)
    {
        parent::__construct('edges');

        self::setSelectionSet(
            [
                    'cursor',
                    (new Query('node'))
                        ->setSelectionSet(
                            $fields
                        )
                ]
        );
    }
}
