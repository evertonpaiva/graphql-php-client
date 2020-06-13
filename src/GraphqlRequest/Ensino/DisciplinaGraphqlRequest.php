<?php

namespace GraphqlClient\GraphqlRequest\Ensino;

use GraphQL\Variable;
use GraphqlClient\GraphqlQuery\RelationQuery;
use GraphqlClient\GraphqlQuery\RelationType;
use GraphqlClient\GraphqlRequest\AuthType;
use GraphqlClient\GraphqlRequest\GraphqlRequest;
use GraphqlClient\GraphqlQuery\PaginationQuery;

/**
 * Class AuthGraphqlRequest
 * Informações de disciplinas
 *
 * @package GraphqlClient\GraphqlRequest
 */
class DisciplinaGraphqlRequest extends GraphqlRequest
{

    public function __construct()
    {
        $fields = [
            'disciplina',
            'nome',
            'iddepto',
            'creditostotal',
            'cargahorariatotal'
        ];

        $authType = AuthType::APP_USER_AUTH;

        parent::__construct($fields, $authType);
    }

    /**
     * Realiza busca por código da disciplina
     * @param $disciplina código da disciplina
     * @return DisciplinaGraphqlRequest
     */
    public function queryGetById($disciplina)
    {
        $this->clearQueryObjects();
        $this->queryName = 'ensinoDisciplina';

        $this->variablesNames[] = new Variable('disciplina', 'String', true);
        $this->variablesValues['disciplina'] = $disciplina;
        $this->arguments = ['disciplina' => '$disciplina'];

        $this->generateSingleQuery();

        return $this;
    }

    public function addRelationDepartamento($departamento = null){
        $this->addRelation(
            new RelationQuery(
                RelationType::SINGLE,
                'objDepartamento',
                DepartamentoGraphqlRequest::class,
                $departamento
            )
        );

        return $this;
    }

    /**
     * Lista de disciplinas
     * @param PaginationQuery $pagination informações de paginação
     * @return DisciplinaGraphqlRequest
     */
    public function queryList(PaginationQuery $pagination)
    {
        $this->clearQueryObjects();
        $this->queryName = 'ensinoDisciplinas';
        $this->pagination = $pagination;

        return $this->generatePaginatedQuery();
    }
}
