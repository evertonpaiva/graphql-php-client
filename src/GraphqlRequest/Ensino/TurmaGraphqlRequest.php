<?php

namespace GraphqlClient\GraphqlRequest\Ensino;

use GraphQL\Variable;
use GraphqlClient\GraphqlQuery\RelationQuery;
use GraphqlClient\GraphqlQuery\RelationType;
use GraphqlClient\GraphqlRequest\AuthType;
use GraphqlClient\GraphqlRequest\GraphqlRequest;
use GraphqlClient\GraphqlQuery\PaginationQuery;

/**
 * Class TurmaGraphqlRequest
 * Informações de turma
 *
 * @package GraphqlClient\GraphqlRequest
 */
class TurmaGraphqlRequest extends GraphqlRequest
{

    public function __construct()
    {
        $fields = [
            'idturma',
            'disciplina',
            'turma',
            'ano',
            'semestre',
            'situacao',
        ];

        $authType = AuthType::APP_USER_AUTH;

        parent::__construct($fields, $authType);
    }

    /**
     * Realiza busca por idturma
     * @param $idturma código de identificação da turma
     * @return TurmaGraphqlRequest
     */
    public function queryGetById($idturma)
    {
        $this->clearQueryObjects();
        $this->queryName = 'ensinoTurma';

        $this->variablesNames[] = new Variable('idturma', 'Int', true);

        $this->variablesValues['idturma'] = $idturma;

        $this->arguments = [
            'idturma' => '$idturma',
        ];

        $this->generateSingleQuery();

        return $this;
    }

    /**
     * Lista de turmas
     * @param PaginationQuery $pagination informações de paginação
     * @param null $disciplina código da disciplina
     * @param null $turma letra de identificação da turma
     * @param null $ano ano da turma
     * @param null $semestre semestre da turma
     * @return ProgramaGraphqlRequest
     */
    public function queryList(PaginationQuery $pagination, $disciplina = null, $turma = null, $ano = null, $sem = null)
    {
        $this->clearQueryObjects();
        $this->queryName = 'ensinoTurmas';
        $this->pagination = $pagination;

        if (!is_null($disciplina)) {
            $this->variablesNames[] = new Variable('disciplina', 'String', false);
            $this->variablesValues['disciplina'] = $disciplina;
            $this->arguments['disciplina'] = '$disciplina';
        }

        if (!is_null($turma)) {
            $this->variablesNames[] = new Variable('turma', 'String', false);
            $this->variablesValues['turma'] = $turma;
            $this->arguments['turma'] = '$turma';
        }

        if (!is_null($ano)) {
            $this->variablesNames[] = new Variable('ano', 'String', false);
            $this->variablesValues['ano'] = $ano;
            $this->arguments['ano'] = '$ano';
        }

        if (!is_null($sem)) {
            $this->variablesNames[] = new Variable('semestre', 'String', false);
            $this->variablesValues['semestre'] = $sem;
            $this->arguments['semestre'] = '$semestre';
        }

        return $this->generatePaginatedQuery();
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
}
