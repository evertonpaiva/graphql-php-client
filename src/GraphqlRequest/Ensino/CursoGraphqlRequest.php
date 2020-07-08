<?php

namespace GraphqlClient\GraphqlRequest\Ensino;

use GraphQL\Variable;
use GraphqlClient\Exception\WrongInstancePaginationException;
use GraphqlClient\GraphqlRequest\AuthType;
use GraphqlClient\GraphqlRequest\GraphqlRequest;
use GraphqlClient\GraphqlQuery\PaginationQuery;

/**
 * Class AuthGraphqlRequest
 * Informações de curso
 *
 * @package GraphqlClient\GraphqlRequest
 */
class CursoGraphqlRequest extends GraphqlRequest
{

    public function __construct()
    {
        $fields = [
            'curso',
            'nome'
        ];

        $authType = AuthType::APP_USER_AUTH;

        parent::__construct($fields, $authType);
    }

    /**
     * Realiza busca por código de curso
     * @param $curso código do curso
     * @return CursoGraphqlRequest
     */
    public function queryGetById($curso)
    {
        $this->clearQueryObjects();
        $this->queryName = 'ensinoCurso';

        $this->variablesNames[] = new Variable('curso', 'String', true);
        $this->variablesValues['curso'] = $curso;
        $this->arguments = ['curso' => '$curso'];

        $this->generateSingleQuery();

        return $this;
    }

    /**
     * Lista de cursos
     * @param PaginationQuery $pagination informações de paginação
     * @return CursoGraphqlRequest
     * @throws WrongInstancePaginationException
     */
    public function queryList(PaginationQuery $pagination)
    {
        $this->clearQueryObjects();
        $this->queryName = 'ensinoCursos';
        $this->pagination = $pagination;

        return $this->generatePaginatedQuery();
    }
}
