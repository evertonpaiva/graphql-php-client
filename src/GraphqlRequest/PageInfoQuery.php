<?php

namespace GraphqlClient\GraphqlRequest;

use GraphQL\Query;

/**
 * Class PageInfoQuery
 * Query GraphQL para os campos com informação de navegação da paginação
 *
 *  pageInfo {
 *    hasNextPage
 *    hasPreviousPage
 *    startCursor
 *    endCursor
 *  }
 * @package GraphqlClient\GraphqlRequest
 */
class PageInfoQuery extends Query
{

    public function __construct()
    {
        parent::__construct('pageInfo');

        self::setSelectionSet(
            [
                'hasNextPage',
                'hasPreviousPage',
                'startCursor',
                'endCursor'
            ]
        );
    }
}
