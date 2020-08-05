<?php

namespace GraphqlClient\GraphqlRequest\Administracao;

use GraphQL\Variable;
use GraphqlClient\GraphqlRequest\AuthType;
use GraphqlClient\GraphqlRequest\GraphqlRequest;
use GraphqlClient\GraphqlQuery\PaginationQuery;

/**
 * Class SetorGraphqlRequest
 * Informações de setor
 *
 * @package GraphqlClient\GraphqlRequest
 */
class SetorGraphqlRequest extends GraphqlRequest
{

    public function __construct()
    {
        $fields = [
            'idsetor',
            'siglasetor',
            'dataini',
            'nomesetor',
            'tiposetor',
            'datafim',
            'fone',
            'fax',
            'centrocusto',
            'obs',
            'localizacao',
            'paisetor',
            'pairelat',
            'idsetorsiape',
            'idpaisetor',
            'idcampi',
            'unidadeprotocoladora'
        ];

        $authType = AuthType::APP_USER_AUTH;

        parent::__construct($fields, $authType);
    }

    /**
     * Realiza busca por código de setor
     * @param $idsetor código de setor
     * @return SetorGraphqlRequest
     */
    public function queryGetById($idsetor)
    {
        $this->clearQueryObjects();
        $this->queryName = 'administracaoSetor';

        $this->variablesNames[] = new Variable('idsetor', 'Int', true);
        $this->variablesValues['idsetor'] = $idsetor;
        $this->arguments = ['idsetor' => '$idsetor'];

        $this->generateSingleQuery();

        return $this;
    }

    /**
     * Lista de setores
     * @param PaginationQuery $pagination informações de paginação
     * @return SetorGraphqlRequest
     */
    public function queryList(PaginationQuery $pagination)
    {
        $this->clearQueryObjects();
        $this->queryName = 'administracaoSetores';
        $this->pagination = $pagination;

        return $this->generatePaginatedQuery();
    }
}
