<?php

namespace GraphqlClient\GraphqlRequest\Administracao;

use GraphQL\Variable;
use GraphqlClient\GraphqlRequest\AuthType;
use GraphqlClient\GraphqlRequest\GraphqlRequest;
use GraphqlClient\GraphqlQuery\PaginationQuery;

/**
 * Class AuthGraphqlRequest
 * Informações de material
 *
 * @package GraphqlClient\GraphqlRequest
 */
class MaterialGraphqlRequest extends GraphqlRequest
{

    public function __construct()
    {
        $fields = [
            'idmaterial',
            'codigocatmat',
            'codigoncm',
            'descricao',
            'idelemento',
            'idsubelemento',
            'unidade',
            'permcons',
            'ativo'
        ];

        $authType = AuthType::APP_USER_AUTH;

        parent::__construct($fields, $authType);
    }

    /**
     * Realiza busca por código de material
     * @param $idmaterial código de material
     * @return MaterialGraphqlRequest
     */
    public function queryGetById($idmaterial)
    {
        $this->clearQueryObjects();
        $this->queryName = 'administracaoMaterial';

        $this->variablesNames[] = new Variable('idmaterial', 'Int', true);
        $this->variablesValues['idmaterial'] = $idmaterial;
        $this->arguments = ['idmaterial' => '$idmaterial'];

        $this->generateSingleQuery();

        return $this;
    }

    /**
     * Lista de materiais
     * @param PaginationQuery $pagination informações de paginação
     * @return MaterialGraphqlRequest
     */
    public function queryList(PaginationQuery $pagination, $descricao = null)
    {
        $this->clearQueryObjects();
        $this->queryName = 'administracaoMateriais';
        $this->pagination = $pagination;

        if (!is_null($descricao)) {
            $this->variablesNames[] = new Variable('descricao', 'String', true);
            $this->variablesValues['descricao'] = $descricao;
            $this->arguments = ['descricao' => '$descricao'];
        }

        return $this->generatePaginatedQuery();
    }
}
