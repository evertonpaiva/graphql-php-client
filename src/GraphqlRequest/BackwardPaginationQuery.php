<?php

namespace GraphqlClient\GraphqlRequest;

/**
 * Class BackwardPaginationQuery
 * Realiza paginação para frente
 *
 * @package GraphqlClient\GraphqlRequest
 */
class BackwardPaginationQuery extends PaginationQuery
{

    public function __construct(int $last = 10, String $before = null)
    {
        parent::__construct($last, $before);
    }

    /**
     * Nome do campo que informa o tamanho
     * @return mixed
     */
    public function getSizeName()
    {
        return 'last';
    }

    /**
     * Nome do campo que informa o tipo de cursor
     * @return mixed
     */
    public function getCursorName()
    {
        return 'before';
    }
}
