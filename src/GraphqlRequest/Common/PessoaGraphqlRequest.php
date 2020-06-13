<?php

namespace GraphqlClient\GraphqlRequest\Common;

use GraphQL\Variable;
use GraphqlClient\GraphqlQuery\RelationQuery;
use GraphqlClient\GraphqlQuery\RelationType;
use GraphqlClient\GraphqlRequest\AuthType;
use GraphqlClient\GraphqlRequest\Ensino\DocenteGraphqlRequest;
use GraphqlClient\GraphqlRequest\GraphqlRequest;
use GraphqlClient\GraphqlQuery\PaginationQuery;

/**
 * Class AuthGraphqlRequest
 * Informações de pessoa
 *
 * @package GraphqlClient\GraphqlRequest
 */
class PessoaGraphqlRequest extends GraphqlRequest
{

    public function __construct()
    {
        $fields = [
            'idpessoa',
            'nome',
            'cpf',
            'sexo',
            'nomepai',
            'containstitucional',
        ];

        $authType = AuthType::APP_USER_AUTH;

        parent::__construct($fields, $authType);
    }

    /**
     * Realiza busca por código de pessoa
     * @param $idpessoa código do pessoa
     * @return PessoaGraphqlRequest
     */
    public function queryGetById($idpessoa)
    {
        $this->clearQueryObjects();
        $this->queryName = 'commonPessoa';

        $this->variablesNames[] = new Variable('idpessoa', 'Int', true);
        $this->variablesValues['idpessoa'] = $idpessoa;
        $this->arguments = ['idpessoa' => '$idpessoa'];

        $this->generateSingleQuery();

        return $this;
    }

    public function addRelationDocentes($docente = null, $pagination = null, $filters = null)
    {
        $this->addRelation(
            new RelationQuery(
                RelationType::PAGINATED,
                'docentes',
                DocenteGraphqlRequest::class,
                $docente,
                $pagination,
                $filters
            )
        );

        return $this;
    }

    /**
     * Lista de pessoas
     * @param PaginationQuery $pagination informações de paginação
     * @return PessoaGraphqlRequest
     */
    public function queryList($pagination = null)
    {
        $this->clearQueryObjects();
        $this->queryName = 'commonPessoas';
        $this->pagination = $pagination;

        return $this->generatePaginatedQuery();
    }
}
