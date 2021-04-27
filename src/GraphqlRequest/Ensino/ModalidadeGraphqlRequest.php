<?php

namespace GraphqlClient\GraphqlRequest\Ensino;

use GraphQL\Variable;
use GraphqlClient\GraphqlRequest\AuthType;
use GraphqlClient\GraphqlRequest\GraphqlRequest;
use GraphqlClient\GraphqlQuery\PaginationQuery;

/**
 * Class AuthGraphqlRequest
 * Informações de modalidade de curso
 *
 * @package GraphqlClient\GraphqlRequest
 */
class ModalidadeGraphqlRequest extends GraphqlRequest
{

    public function __construct()
    {
        $fields = [
            'idmodalidade',
            'modalidade'
        ];

        $authType = AuthType::APP_USER_AUTH;

        parent::__construct($fields, $authType);
    }

    /**
     * Realiza busca por modalidade de curso
     * @param $idmodalidade código de modalidade de curso
     * @return ModalidadeGraphqlRequest
     */
    public function queryGetById($idmodalidade)
    {
        $this->clearQueryObjects();
        $this->queryName = 'ensinoModalidade';

        $this->variablesNames[] = new Variable('idmodalidade', 'Int', true);
        $this->variablesValues['idmodalidade'] = $idmodalidade;
        $this->arguments = ['idmodalidade' => '$idmodalidade'];

        $this->generateSingleQuery();

        return $this;
    }

    /**
     * Lista de modalidades de curso
     * @param PaginationQuery $pagination informações de paginação
     * @return ModalidadeGraphqlRequest
     */
    public function queryList(PaginationQuery $pagination)
    {
        $this->clearQueryObjects();
        $this->queryName = 'ensinoModalidades';
        $this->pagination = $pagination;

        return $this->generatePaginatedQuery();
    }
}
