<?php

namespace GraphqlClient\GraphqlRequest\Ensino;

use GraphQL\Variable;
use GraphqlClient\GraphqlQuery\RelationQuery;
use GraphqlClient\GraphqlQuery\RelationType;
use GraphqlClient\GraphqlRequest\AuthType;
use GraphqlClient\GraphqlRequest\GraphqlRequest;
use GraphqlClient\GraphqlQuery\PaginationQuery;

/**
 * Class MatriculaGraphqlRequest
 * Informações de matricula
 *
 * @package GraphqlClient\GraphqlRequest
 */
class MatriculaGraphqlRequest extends GraphqlRequest
{

    public function __construct()
    {
        $fields = [
            'matricula',
            'idturma',
            'nota',
            'segundaepoca',
            'freq',
            'situacao',
            'tipo',
        ];

        $authType = AuthType::APP_USER_AUTH;

        parent::__construct($fields, $authType);
    }

    /**
     * Realiza busca por idturma
     * @param $idturma integer código de identificação da turma
     * @param $matricula string matrícula do aluno
     * @return MatriculaGraphqlRequest
     */
    public function queryGetById($idturma, $matricula)
    {
        $this->clearQueryObjects();
        $this->queryName = 'ensinoMatricula';

        $this->variablesNames[] = new Variable('idturma', 'Int', true);
        $this->variablesNames[] = new Variable('matricula', 'String', true);

        $this->variablesValues['idturma'] = $idturma;
        $this->variablesValues['matricula'] = $matricula;

        $this->arguments = [
            'idturma' => '$idturma',
            'matricula' => '$matricula',
        ];

        $this->generateSingleQuery();

        return $this;
    }

    /**
     * Lista de matrículas
     * @param PaginationQuery $pagination informações de paginação
     * @param null $matricula matrícula do aluno
     * @param null $idturma código de identificação da turma
     * @return MatriculaGraphqlRequest
     */
    public function queryList(PaginationQuery $pagination, $matricula = null, $idturma = null)
    {
        $this->clearQueryObjects();
        $this->queryName = 'ensinoMatriculas';
        $this->pagination = $pagination;

        if (!is_null($matricula)) {
            $this->variablesNames[] = new Variable('matricula', 'String', false);
            $this->variablesValues['matricula'] = $matricula;
            $this->arguments['matricula'] = '$matricula';
        }

        if (!is_null($idturma)) {
            $this->variablesNames[] = new Variable('idturma', 'Int', false);
            $this->variablesValues['idturma'] = $matricula;
            $this->arguments['idturma'] = '$idturma';
        }

        return $this->generatePaginatedQuery();
    }

    public function addRelationTurma()
    {
        $this->addRelation(
            new RelationQuery(
                RelationType::SINGLE,
                'objTurma',
                TurmaGraphqlRequest::class
            )
        );

        return $this;
    }

    public function addRelationAluno()
    {
        $this->addRelation(
            new RelationQuery(
                RelationType::SINGLE,
                'objAluno',
                AlunoGraphqlRequest::class
            )
        );

        return $this;
    }
}
