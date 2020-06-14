<?php

namespace GraphqlClient\GraphqlRequest\Common;

use GraphQL\Variable;
use GraphqlClient\GraphqlRequest\AuthType;
use GraphqlClient\GraphqlRequest\GraphqlRequest;
use GraphqlClient\GraphqlQuery\PaginationQuery;

/**
 * Class RacaGraphqlRequest
 * Informações de etnia declarada
 *
 * @package GraphqlClient\GraphqlRequest\Common
 */
class RacaGraphqlRequest extends GraphqlRequest
{

    public function __construct()
    {
        $fields = [
            'idraca',
            'raca'
        ];

        $authType = AuthType::APP_USER_AUTH;

        parent::__construct($fields, $authType);
    }

    /**
     * Realiza busca por código da raça
     * @param $idraca código da raça
     * @return RacaGraphqlRequest
     */
    public function queryGetById($idraca)
    {
        $this->clearQueryObjects();
        $this->queryName = 'commonRaca';

        $this->variablesNames[] = new Variable('idraca', 'Int', true);
        $this->variablesValues['idraca'] = $idraca;
        $this->arguments = ['idraca' => '$idraca'];

        $this->generateSingleQuery();

        return $this;
    }

    /**
     * Lista de raças
     * @param PaginationQuery $pagination informações de paginação
     * @return RacaGraphqlRequest
     */
    public function queryList($pagination = null)
    {
        $this->clearQueryObjects();
        $this->queryName = 'commonRacas';
        $this->pagination = $pagination;

        return $this->generatePaginatedQuery();
    }
}
