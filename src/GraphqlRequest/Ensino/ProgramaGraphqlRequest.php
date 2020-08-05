<?php

namespace GraphqlClient\GraphqlRequest\Ensino;

use GraphQL\Variable;
use GraphqlClient\GraphqlQuery\RelationQuery;
use GraphqlClient\GraphqlQuery\RelationType;
use GraphqlClient\GraphqlRequest\AuthType;
use GraphqlClient\GraphqlRequest\Ensino\CursoGraphqlRequest;
use GraphqlClient\GraphqlRequest\GraphqlRequest;
use GraphqlClient\GraphqlQuery\PaginationQuery;

/**
 * Class AuthGraphqlRequest
 * Informações de programa
 *
 * @package GraphqlClient\GraphqlRequest
 */
class ProgramaGraphqlRequest extends GraphqlRequest
{

    public function __construct()
    {
        $fields = [
            'matricula',
            'curso',
            'curriculo'
        ];

        $authType = AuthType::APP_USER_AUTH;

        parent::__construct($fields, $authType);
    }

    /**
     * Realiza busca por matrícula de aluno
     * @param $matricula matrícula do aluno
     * @return ProgramdGraphqlRequest
     */
    public function queryGetById($matricula)
    {
        $this->clearQueryObjects();
        $this->queryName = 'ensinoPrograma';

        $this->variablesNames[] = new Variable('matricula', 'String', true);
        $this->variablesValues['matricula'] = $matricula;
        $this->arguments = ['matricula' => '$matricula'];

        $this->generateSingleQuery();

        return $this;
    }

    /**
     * Lista de programas
     * @param PaginationQuery $pagination informações de paginação
     * @return ProgramaGraphqlRequest
     */
    public function queryList(PaginationQuery $pagination)
    {
        $this->clearQueryObjects();
        $this->queryName = 'ensinoProgramas';
        $this->pagination = $pagination;

        return $this->generatePaginatedQuery();
    }

    public function addRelationCurso($curso = null, $pagination = null)
    {
        $this->addRelation(
            new RelationQuery(
                RelationType::SINGLE,
                'objCurso',
                CursoGraphqlRequest::class,
                $curso,
                $pagination
            )
        );

        return $this;
    }
}
