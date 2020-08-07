<?php

namespace GraphqlClient\GraphqlRequest\Rh;

use GraphQL\Variable;
use GraphqlClient\GraphqlQuery\RelationQuery;
use GraphqlClient\GraphqlQuery\RelationType;
use GraphqlClient\GraphqlRequest\AuthType;
use GraphqlClient\GraphqlRequest\Common\PessoaGraphqlRequest;
use GraphqlClient\GraphqlRequest\Administracao\SetorGraphqlRequest;
use GraphqlClient\GraphqlRequest\GraphqlRequest;
use GraphqlClient\GraphqlQuery\PaginationQuery;

/**
 * Class AuthGraphqlRequest
 * Informações de servidor
 *
 * @package GraphqlClient\GraphqlRequest
 */
class ServidorGraphqlRequest extends GraphqlRequest
{

    public function __construct()
    {
        $fields = [
            'idfuncionario',
            'idvinculo',
            'idpessoa',
            'idsetor',
            'cargo',
            'situacao'
        ];

        $authType = AuthType::APP_USER_AUTH;

        parent::__construct($fields, $authType);
    }

    /**
     * Realiza busca por código de funcionário de servidor
     * @param $idfuncionario código de funcionário do servidor
     * @return ServidorGraphqlRequest
     */
    public function queryGetById($idfuncionario)
    {
        $this->clearQueryObjects();
        $this->queryName = 'rhServidor';

        $this->variablesNames[] = new Variable('idfuncionario', 'String', true);
        $this->variablesValues['idfuncionario'] = $idfuncionario;
        $this->arguments = ['idfuncionario' => '$idfuncionario'];

        $this->generateSingleQuery();

        return $this;
    }

    /**
     * Lista de funcionarios
     * @param PaginationQuery $pagination informações de paginação
     * @return ServidorGraphqlRequest
     */
    public function queryList(PaginationQuery $pagination)
    {
        $this->clearQueryObjects();
        $this->queryName = 'rhServidores';
        $this->pagination = $pagination;

        return $this->generatePaginatedQuery();
    }

    /**
     * @param null $pessoa
     * @param null $pagination paginação
     * @return $this
     * @throws \GraphqlClient\Exception\WrongInstancePaginationException
     * @throws \GraphqlClient\Exception\WrongInstanceRelationException
     */
    public function addRelationPessoa($pessoa = null, $pagination = null)
    {
        $this->addRelation(
            new RelationQuery(
                RelationType::SINGLE,
                'objPessoa',
                PessoaGraphqlRequest::class,
                $pessoa,
                $pagination
            )
        );

        return $this;
    }

    /**
     * @param null $setor
     * @param null $pagination paginação
     * @return $this
     * @throws \GraphqlClient\Exception\WrongInstancePaginationException
     * @throws \GraphqlClient\Exception\WrongInstanceRelationException
     */
    public function addRelationSetor($setor = null, $pagination = null)
    {
        $this->addRelation(
            new RelationQuery(
                RelationType::SINGLE,
                'objSetor',
                SetorGraphqlRequest::class,
                $setor,
                $pagination
            )
        );

        return $this;
    }
}
