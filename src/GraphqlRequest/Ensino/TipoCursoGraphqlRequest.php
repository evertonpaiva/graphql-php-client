<?php

namespace GraphqlClient\GraphqlRequest\Ensino;

use GraphQL\Variable;
use GraphqlClient\GraphqlRequest\AuthType;
use GraphqlClient\GraphqlRequest\GraphqlRequest;
use GraphqlClient\GraphqlQuery\PaginationQuery;

/**
 * Class AuthGraphqlRequest
 * Informações de tipo de curso
 *
 * @package GraphqlClient\GraphqlRequest
 */
class TipoCursoGraphqlRequest extends GraphqlRequest
{

    public function __construct()
    {
        $fields = [
            'idtipocurso',
            'tipocurso'
        ];

        $authType = AuthType::APP_USER_AUTH;

        parent::__construct($fields, $authType);
    }

    /**
     * Realiza busca por código de tipo de curso
     * @param $idtipocurso código de tipo de curso
     * @return TipoCursoGraphqlRequest
     */
    public function queryGetById($idtipocurso)
    {
        $this->clearQueryObjects();
        $this->queryName = 'ensinoTipoCurso';

        $this->variablesNames[] = new Variable('idtipocurso', 'String', true);
        $this->variablesValues['idtipocurso'] = $idtipocurso;
        $this->arguments = ['idtipocurso' => '$idtipocurso'];

        $this->generateSingleQuery();

        return $this;
    }

    /**
     * Lista de tipos de curso
     * @param PaginationQuery $pagination informações de paginação
     * @return TipoCursoGraphqlRequest
     */
    public function queryList(PaginationQuery $pagination)
    {
        $this->clearQueryObjects();
        $this->queryName = 'ensinoTiposCurso';
        $this->pagination = $pagination;

        return $this->generatePaginatedQuery();
    }
}
