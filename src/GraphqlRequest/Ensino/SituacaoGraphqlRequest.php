<?php

namespace GraphqlClient\GraphqlRequest\Ensino;

use GraphQL\Variable;
use GraphqlClient\GraphqlRequest\AuthType;
use GraphqlClient\GraphqlRequest\GraphqlRequest;
use GraphqlClient\GraphqlQuery\PaginationQuery;

/**
 * Class AuthGraphqlRequest
 * Informações de situação de aluno
 *
 * @package GraphqlClient\GraphqlRequest
 */
class SituacaoGraphqlRequest extends GraphqlRequest
{

    public function __construct()
    {
        $fields = [
            'idsituacao',
            'situacao'
        ];

        $authType = AuthType::APP_USER_AUTH;

        parent::__construct($fields, $authType);
    }

    /**
     * Realiza busca por situação de aluno
     * @param $idsituacao código da situação do aluno
     * @return SituacaoGraphqlRequest
     */
    public function queryGetById($idsituacao)
    {
        $this->clearQueryObjects();
        $this->queryName = 'ensinoSituacao';

        $this->variablesNames[] = new Variable('idsituacao', 'String', true);
        $this->variablesValues['idsituacao'] = $idsituacao;
        $this->arguments = ['idsituacao' => '$idsituacao'];

        $this->generateSingleQuery();

        return $this;
    }

    /**
     * Lista de situações de aluno
     * @param PaginationQuery $pagination informações de paginação
     * @return SituacaoGraphqlRequest
     */
    public function queryList(PaginationQuery $pagination)
    {
        $this->clearQueryObjects();
        $this->queryName = 'ensinoSituacoes';
        $this->pagination = $pagination;

        return $this->generatePaginatedQuery();
    }
}
