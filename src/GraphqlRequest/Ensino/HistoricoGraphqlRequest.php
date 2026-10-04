<?php

namespace GraphqlClient\GraphqlRequest\Ensino;

use GraphQL\Variable;
use GraphqlClient\GraphqlQuery\RelationQuery;
use GraphqlClient\GraphqlQuery\RelationType;
use GraphqlClient\GraphqlRequest\AuthType;
use GraphqlClient\GraphqlRequest\GraphqlRequest;
use GraphqlClient\GraphqlQuery\PaginationQuery;

/**
 * Class HistoricoGraphqlRequest
 * Informações de histórico escolar
 *
 * @package GraphqlClient\GraphqlRequest
 */
class HistoricoGraphqlRequest extends GraphqlRequest
{

    public function __construct()
    {
        $fields = [
            'matricula',
            'disciplina',
            'ano',
            'semestre',
            'idconceito',
            'nota',
            'frequencia',
            'segundaepoca',
            'idturma',
            'tipo',
        ];

        $authType = AuthType::APP_USER_AUTH;

        parent::__construct($fields, $authType);
    }

    /**
     * Realiza busca por registro de histórico do aluno
     * @param string $matricula matrícula do aluno
     * @param string $disciplina código da disciplina
     * @param string $ano ano letivo
     * @param string $semestre semestre letivo
     * @return HistoricoGraphqlRequest
     */
    public function queryGetById($matricula, $disciplina, $ano, $semestre)
    {
        $this->clearQueryObjects();
        $this->queryName = 'ensinoHistoricoRegistroPorAluno';

        $this->variablesNames[] = new Variable('matricula', 'String', true);
        $this->variablesNames[] = new Variable('disciplina', 'String', true);
        $this->variablesNames[] = new Variable('ano', 'String', true);
        $this->variablesNames[] = new Variable('semestre', 'String', true);

        $this->variablesValues['matricula'] = $matricula;
        $this->variablesValues['disciplina'] = $disciplina;
        $this->variablesValues['ano'] = $ano;
        $this->variablesValues['semestre'] = $semestre;

        $this->arguments = [
            'matricula' => '$matricula',
            'disciplina' => '$disciplina',
            'ano' => '$ano',
            'semestre' => '$semestre',
        ];

        $this->generateSingleQuery();

        return $this;
    }

    /**
     * Lista histórico escolar por aluno
     * @param PaginationQuery $pagination informações de paginação
     * @param string $matricula matrícula do aluno
     * @return HistoricoGraphqlRequest
     */
    public function queryList(PaginationQuery $pagination, $matricula)
    {
        $this->clearQueryObjects();
        $this->queryName = 'ensinoHistoricoPorAluno';
        $this->pagination = $pagination;

        $this->variablesNames[] = new Variable('matricula', 'String', true);
        $this->variablesValues['matricula'] = $matricula;
        $this->arguments['matricula'] = '$matricula';

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

    public function addRelationDisciplina()
    {
        $this->addRelation(
            new RelationQuery(
                RelationType::SINGLE,
                'objDisciplina',
                DisciplinaGraphqlRequest::class
            )
        );

        return $this;
    }
}
