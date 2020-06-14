<?php

namespace GraphqlClient\GraphqlQuery;

/**
 * Class ForwardPaginationQuery
 * Realiza paginação para frente
 *
 * @package GraphqlClient\GraphqlRequest
 */
class ForwardPaginationQuery extends PaginationQuery
{

    public function __construct(int $first = 10, String $after = null)
    {
        parent::__construct($first, $after);
    }

    /**
     * Nome do campo que informa o tamanho
     * @return mixed
     */
    public function getSizeName()
    {
        return 'first';
    }

    /**
     * Nome do campo que informa o tipo de cursor
     * @return mixed
     */
    public function getCursorName()
    {
        return 'after';
    }
}
