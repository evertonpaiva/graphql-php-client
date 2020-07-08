<?php
namespace GraphqlClient\Tests\Administracao;

use GraphqlClient\GraphqlRequest\Administracao\MaterialGraphqlRequest;
use GraphqlClient\GraphqlQuery\ForwardPaginationQuery;
use GraphqlClient\Tests\GraphqlRequestTest;
use stdClass;

class MaterialGraphqlRequestTest extends GraphqlRequestTest
{
    /**
     * Materiais por código
     */
    public function testMaterialQueryGetById()
    {
        // Carrega a classe de material
        $materialGraphqlRequest = new MaterialGraphqlRequest();

        // Recupera informações de material por código
        $material = $materialGraphqlRequest->queryGetById(2)->getResults();

        $expected = new stdClass;
        $expected->idmaterial = 2;
        $expected->codigocatmat = null;
        $expected->codigoncm = null;
        $expected->descricao = 'ÁCIDO FOSFÓRICO 37% - GEL, CAIXA COM 1 BISNAGA PLÁSTICA DE 5 ML, CAIXA COM 1 SERINGA DE 3 ML';
        $expected->idelemento = '339030';
        $expected->idsubelemento = '10';
        $expected->unidade = 'CAIXA';
        $expected->permcons = 'C';
        $expected->ativo = 'N';

        $this->assertEquals($expected, $material);
    }

    /**
     * Lista de materiais
     */
    public function testMaterialQueryList()
    {
        // Carrega a classe de material
        $materialGraphqlRequest = new MaterialGraphqlRequest();

        $pagination = new ForwardPaginationQuery(3);
        $materiais = $materialGraphqlRequest->queryList($pagination)->getResults();

        $this->assertIsArray($materiais->edges);
        $this->assertIsObject($materiais->pageInfo);
    }

    /**
     * Lista de materiais filtrando por descrição
     */
    public function testMaterialQueryListFilterDescricao()
    {
        // Carrega a classe de material
        $materialGraphReq = new MaterialGraphqlRequest();

        $pagination = new ForwardPaginationQuery(3);
        $materiais = $materialGraphReq->queryList($pagination, 'cadeira')->getResults();

        $this->assertIsArray($materiais->edges);
        $this->assertIsObject($materiais->pageInfo);
    }
}
