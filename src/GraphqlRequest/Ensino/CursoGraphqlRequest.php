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
 * Class AuthGraphqlRequest
 * Informações de curso
 *
 * @package GraphqlClient\GraphqlRequest
 */
class CursoGraphqlRequest extends GraphqlRequest
{

    public function __construct()
    {
        $fields = [
            'curso',
            'nome'
        ];

        $authType = AuthType::APP_USER_AUTH;

        parent::__construct($fields, $authType);
    }

    /**
     * Realiza busca por código de curso
     * @param $curso código do curso
     * @return CursoGraphqlRequest
     */
    public function queryGetById($curso)
    {
        $this->clearQueryObjects();
        $this->queryName = 'ensinoCurso';

        $this->variablesNames[] = new Variable('curso', 'String', true);
        $this->variablesValues['curso'] = $curso;
        $this->arguments = ['curso' => '$curso'];

        $this->generateSingleQuery();

        return $this;
    }

    /**
     * Lista de cursos
     * @param PaginationQuery $pagination informações de paginação
     * @param string $nome nome ou parte do nome da pessoa
     * @return CursoGraphqlRequest
     * @throws WrongInstancePaginationException
     */
    public function queryList(PaginationQuery $pagination, $nome = null)
    {
        $this->clearQueryObjects();
        $this->queryName = 'ensinoCursos';
        $this->pagination = $pagination;

        if (!is_null($nome)) {
            $this->variablesNames[] = new Variable('nome', 'String', false);
            $this->variablesValues['nome'] = $nome;
            $this->arguments = ['nome' => '$nome'];
        }

        return $this->generatePaginatedQuery();
    }

    public function addRelationTipoCurso($idtipocurso = null, $pagination = null)
    {
        $this->addRelation(
            new RelationQuery(
                RelationType::SINGLE,
                'objTipoCurso',
                TipoCursoGraphqlRequest::class,
                $idtipocurso,
                $pagination
            )
        );

        return $this;
    }

    public function addRelationModalidade($idmodalidade = null, $pagination = null)
    {
        $this->addRelation(
            new RelationQuery(
                RelationType::SINGLE,
                'objModalidade',
                ModalidadeGraphqlRequest::class,
                $idmodalidade,
                $pagination
            )
        );

        return $this;
    }
}
