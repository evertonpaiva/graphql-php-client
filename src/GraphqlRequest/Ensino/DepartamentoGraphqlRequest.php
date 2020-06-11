<?php

namespace GraphqlClient\GraphqlRequest\Ensino;

use GraphQL\Variable;
use GraphqlClient\GraphqlRequest\AuthType;
use GraphqlClient\GraphqlRequest\GraphqlRequest;
use GraphqlClient\GraphqlRequest\PaginationQuery;

/**
 * Class AuthGraphqlRequest
 * Informações de disciplinas
 *
 * @package GraphqlClient\GraphqlRequest
 */
class DepartamentoGraphqlRequest extends GraphqlRequest
{

    public function __construct()
    {
        $fields = [
            'iddepto',
            'depto',
            'nome'
        ];

        $authType = AuthType::APP_USER_AUTH;

        parent::__construct($fields, $authType);
    }

    /**
     * Realiza busca por código do departamento
     * @param $iddepto código do departamento
     * @return $this
     */
    public function queryGetById($iddepto)
    {
        $this->clearQueryObjects();
        $this->queryName = 'ensinoDepartamento';

        $this->variablesNames[] = new Variable('iddepto', 'String', true);
        $this->variablesValues['iddepto'] = $iddepto;
        $this->arguments = ['iddepto' => '$iddepto'];

        $this->generateSingleQuery();

        return $this;
    }

    /**
     * Lista de departamentos
     * @param PaginationQuery $pagination informações de paginação
     * @return DisciplinaGraphqlRequest
     */
    public function queryList(PaginationQuery $pagination)
    {
        $this->clearQueryObjects();
        $this->queryName = 'ensinoDepartamentos';
        $this->pagination = $pagination;

        return $this->generatePaginatedQuery();
    }
}
