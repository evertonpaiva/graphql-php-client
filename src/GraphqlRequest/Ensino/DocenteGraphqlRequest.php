<?php

namespace GraphqlClient\GraphqlRequest\Ensino;

use GraphQL\Variable;
use GraphqlClient\GraphqlRequest\AuthType;
use GraphqlClient\GraphqlRequest\GraphqlRequest;
use GraphqlClient\GraphqlQuery\PaginationQuery;

/**
 * Class AuthGraphqlRequest
 * Informações de docente
 *
 * @package GraphqlClient\GraphqlRequest
 */
class DocenteGraphqlRequest extends GraphqlRequest
{

    public function __construct()
    {
        $fields = [
            'iddocente',
            'idpessoa',
            'idvinc',
            'situacao',
            'iddepto'
        ];

        $authType = AuthType::APP_USER_AUTH;

        parent::__construct($fields, $authType);
    }

    /**
     * Realiza busca por código de docente
     * @param $iddocente código do docente
     * @return DocenteGraphqlRequest
     */
    public function queryGetById($iddocente)
    {
        $this->clearQueryObjects();
        $this->queryName = 'ensinoDocente';

        $this->variablesNames[] = new Variable('iddocente', 'Int', true);
        $this->variablesValues['iddocente'] = $iddocente;
        $this->arguments = ['iddocente' => '$iddocente'];

        $this->generateSingleQuery();

        return $this;
    }

    /**
     * Lista de docentes
     * @param PaginationQuery $pagination informações de paginação
     * @return DocenteGraphqlRequest
     */
    public function queryList(PaginationQuery $pagination)
    {
        $this->clearQueryObjects();
        $this->queryName = 'ensinoDocentes';
        $this->pagination = $pagination;

        return $this->generatePaginatedQuery();
    }
}
