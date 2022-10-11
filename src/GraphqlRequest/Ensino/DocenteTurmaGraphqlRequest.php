<?php

namespace GraphqlClient\GraphqlRequest\Ensino;

use GraphQL\Variable;
use GraphqlClient\GraphqlQuery\RelationQuery;
use GraphqlClient\GraphqlQuery\RelationType;
use GraphqlClient\GraphqlRequest\AuthType;
use GraphqlClient\GraphqlRequest\GraphqlRequest;
use GraphqlClient\GraphqlQuery\PaginationQuery;

/**
 * Class DocenteTurmaGraphqlRequest
 * Informações de docentes por turma
 *
 * @package GraphqlClient\GraphqlRequest
 */
class DocenteTurmaGraphqlRequest extends GraphqlRequest
{

    public function __construct()
    {
        $fields = [
            'idturma',
            'iddocente',
            'horasaula',
        ];

        $authType = AuthType::APP_USER_AUTH;

        parent::__construct($fields, $authType);
    }

    /**
     * Realiza busca por idturma
     * @param $idturma código de identificação da turma
     * @param $iddocente código de identificação do docente
     * @return DocenteTurmaGraphqlRequest
     */
    public function queryGetById($idturma, $iddocente)
    {
        $this->clearQueryObjects();
        $this->queryName = 'ensinoDocenteTurma';

        $this->variablesNames[] = new Variable('idturma', 'Int', true);
        $this->variablesNames[] = new Variable('iddocente', 'Int', true);

        $this->variablesValues['idturma'] = $idturma;
        $this->variablesValues['iddocente'] = $iddocente;

        $this->arguments = [
            'idturma' => '$idturma',
            'iddocente' => '$iddocente',
        ];

        $this->generateSingleQuery();

        return $this;
    }

    /**
     * Lista de turmas
     * @param PaginationQuery $pagination informações de paginação
     * @param null $idturma código da turma
     * @param null $iddocente código do docente
     * @return DocenteTurmaGraphqlRequest
     */
    public function queryList(PaginationQuery $pagination, $idturma = null, $iddocente = null)
    {
        $this->clearQueryObjects();
        $this->queryName = 'ensinoDocentesTurmas';
        $this->pagination = $pagination;

        if (!is_null($idturma)) {
            $this->variablesNames[] = new Variable('idturma', 'Int', false);
            $this->variablesValues['idturma'] = $idturma;
            $this->arguments['idturma'] = '$idturma';
        }

        if (!is_null($iddocente)) {
            $this->variablesNames[] = new Variable('iddocente', 'Int', false);
            $this->variablesValues['iddocente'] = $iddocente;
            $this->arguments['iddocente'] = '$iddocente';
        }

        return $this->generatePaginatedQuery();
    }

    public function addRelationTurma($turma = null, $pagination = null)
    {
        $this->addRelation(
            new RelationQuery(
                RelationType::SINGLE,
                'objTurma',
                TurmaGraphqlRequest::class,
                $turma,
                $pagination
            )
        );

        return $this;
    }

    public function addRelationDocente($docente = null, $pagination = null)
    {
        $this->addRelation(
            new RelationQuery(
                RelationType::SINGLE,
                'objDocente',
                DocenteGraphqlRequest::class,
                $docente,
                $pagination
            )
        );

        return $this;
    }
}
