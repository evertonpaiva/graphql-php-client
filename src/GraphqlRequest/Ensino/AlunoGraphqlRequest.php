<?php

namespace GraphqlClient\GraphqlRequest\Ensino;

use GraphQL\Variable;
use GraphqlClient\GraphqlQuery\RelationQuery;
use GraphqlClient\GraphqlQuery\RelationType;
use GraphqlClient\GraphqlRequest\AuthType;
use GraphqlClient\GraphqlRequest\Common\PessoaGraphqlRequest;
use GraphqlClient\GraphqlRequest\Ensino\ProgramaGraphqlRequest;
use GraphqlClient\GraphqlRequest\GraphqlRequest;
use GraphqlClient\GraphqlQuery\PaginationQuery;

/**
 * Class AuthGraphqlRequest
 * Informações de aluno
 *
 * @package GraphqlClient\GraphqlRequest
 */
class AlunoGraphqlRequest extends GraphqlRequest
{

    public function __construct()
    {
        $fields = [
            'matricula',
            'anoingresso',
            'semingresso',
            'idpessoa',
            'cra',
            'percentualconclusao'
        ];

        $authType = AuthType::APP_USER_AUTH;

        parent::__construct($fields, $authType);
    }

    /**
     * Realiza busca por matrícula de aluno
     * @param $matricula matrícula do aluno
     * @return AlunoGraphqlRequest
     */
    public function queryGetById($matricula)
    {
        $this->clearQueryObjects();
        $this->queryName = 'ensinoAluno';

        $this->variablesNames[] = new Variable('matricula', 'String', true);
        $this->variablesValues['matricula'] = $matricula;
        $this->arguments = ['matricula' => '$matricula'];

        $this->generateSingleQuery();

        return $this;
    }

    /**
     * Lista de alunos
     * @param PaginationQuery $pagination informações de paginação
     * @return AlunoGraphqlRequest
     */
    public function queryList(PaginationQuery $pagination)
    {
        $this->clearQueryObjects();
        $this->queryName = 'ensinoAlunos';
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
     * @param null $programa
     * @param null $pagination paginação
     * @return $this
     * @throws \GraphqlClient\Exception\WrongInstancePaginationException
     * @throws \GraphqlClient\Exception\WrongInstanceRelationException
     */
    public function addRelationPrograma($programa = null, $pagination = null)
    {
        $this->addRelation(
            new RelationQuery(
                RelationType::SINGLE,
                'objPrograma',
                ProgramaGraphqlRequest::class,
                $programa,
                $pagination
            )
        );

        return $this;
    }
}
