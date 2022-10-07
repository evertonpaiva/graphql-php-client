<?php

namespace GraphqlClient\GraphqlRequest\Ensino;

use GraphQL\Variable;
use GraphqlClient\GraphqlQuery\RelationQuery;
use GraphqlClient\GraphqlQuery\RelationType;
use GraphqlClient\GraphqlRequest\AuthType;
use GraphqlClient\GraphqlRequest\GraphqlRequest;
use GraphqlClient\GraphqlQuery\PaginationQuery;

/**
 * Class ProgramaGraphqlRequest
 * Informações de grade
 *
 * @package GraphqlClient\GraphqlRequest
 */
class GradeGraphqlRequest extends GraphqlRequest
{

    public function __construct()
    {
        $fields = [
            'curso',
            'curriculo',
            'disciplina',
            'periodo',
        ];

        $authType = AuthType::APP_USER_AUTH;

        parent::__construct($fields, $authType);
    }

    /**
     * Realiza busca por curso, currículo e disciplina
     * @param $curso curso do aluno
     * @param $curriculo curriculo do aluno
     * @param $disciplina disciplina da grade
     * @return GradeGraphqlRequest
     */
    public function queryGetById($curso, $curriculo, $disciplina)
    {
        $this->clearQueryObjects();
        $this->queryName = 'ensinoGrade';

        $this->variablesNames[] = new Variable('curso', 'String', true);
        $this->variablesNames[] = new Variable('curriculo', 'String', true);
        $this->variablesNames[] = new Variable('disciplina', 'String', true);

        $this->variablesValues['curso'] = $curso;
        $this->variablesValues['curriculo'] = $curriculo;
        $this->variablesValues['disciplina'] = $disciplina;

        $this->arguments = [
            'curso' => '$curso',
            'curriculo' => '$curriculo',
            'disciplina' => '$disciplina',
        ];

        $this->generateSingleQuery();

        return $this;
    }

    /**
     * Lista de grades
     * @param PaginationQuery $pagination informações de paginação
     * @return ProgramaGraphqlRequest
     */
    public function queryList(PaginationQuery $pagination, $curso = null, $curriculo = null)
    {
        $this->clearQueryObjects();
        $this->queryName = 'ensinoGrades';
        $this->pagination = $pagination;

        if (!is_null($curso)) {
            $this->variablesNames[] = new Variable('curso', 'String', false);
            $this->variablesValues['curso'] = $curso;
            $this->arguments['curso'] = '$curso';
        }

        if (!is_null($curriculo)) {
            $this->variablesNames[] = new Variable('curriculo', 'String', false);
            $this->variablesValues['curriculo'] = $curriculo;
            $this->arguments['curriculo'] = '$curriculo';
        }

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

    public function addRelationDisciplina($disciplina = null, $pagination = null)
    {
        $this->addRelation(
            new RelationQuery(
                RelationType::SINGLE,
                'objDisciplina',
                DisciplinaGraphqlRequest::class,
                $disciplina,
                $pagination
            )
        );

        return $this;
    }

    public function addRelationCurriculo($curriculo = null, $pagination = null)
    {
        $this->addRelation(
            new RelationQuery(
                RelationType::SINGLE,
                'objCurriculo',
                CurriculoGraphqlRequest::class,
                $curriculo,
                $pagination
            )
        );

        return $this;
    }
}
