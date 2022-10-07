<?php

namespace GraphqlClient\GraphqlRequest\Ensino;

use GraphQL\Variable;
use GraphqlClient\GraphqlQuery\RelationQuery;
use GraphqlClient\GraphqlQuery\RelationType;
use GraphqlClient\Exception\WrongInstancePaginationException;
use GraphqlClient\GraphqlRequest\AuthType;
use GraphqlClient\GraphqlRequest\GraphqlRequest;
use GraphqlClient\GraphqlQuery\PaginationQuery;

/**
 * Class CurriculoGraphqlRequest
 * Informações de curriculo
 *
 * @package GraphqlClient\GraphqlRequest
 */
class CurriculoGraphqlRequest extends GraphqlRequest
{

    public function __construct()
    {
        $fields = [
            'curso',
            'curriculo',
            'anoini',
            'semini',
        ];

        $authType = AuthType::APP_USER_AUTH;

        parent::__construct($fields, $authType);
    }

    /**
     * Realiza busca por curso/currículo
     * @param $curso código do curso
     * @param $curriculo currículo da grade
     * @return CurriculoGraphqlRequest
     */
    public function queryGetById($curso, $curriculo)
    {
        $this->clearQueryObjects();
        $this->queryName = 'ensinoCurriculo';

        $this->variablesNames[] = new Variable('curso', 'String', true);
        $this->variablesNames[] = new Variable('curriculo', 'String', true);
        $this->variablesValues['curso'] = $curso;
        $this->variablesValues['curriculo'] = $curriculo;
        $this->arguments = [
            'curso' => '$curso',
            'curriculo' => '$curriculo',
        ];

        $this->generateSingleQuery();

        return $this;
    }

    /**
     * Lista de cursos
     * @param PaginationQuery $pagination informações de paginação
     * @param $nome nome ou parte do nome da pessoa
     * @param $tipoCurriculo tipo de curso
     * @param $modalidade modalidade de curso
     * @return CurriculoGraphqlRequest
     * @throws WrongInstancePaginationException
     */
    public function queryList(PaginationQuery $pagination)
    {
        $this->clearQueryObjects();
        $this->queryName = 'ensinoCurriculos';
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
