<?php
namespace GraphqlClient\Tests\Administracao;

use GraphqlClient\GraphqlRequest\Administracao\SetorGraphqlRequest;
use GraphqlClient\GraphqlQuery\ForwardPaginationQuery;
use GraphqlClient\Tests\GraphqlRequestTest;
use stdClass;

class SetorGraphqlRequestTest extends GraphqlRequestTest
{
    /**
     * Setor por código
     */
    public function testSetorQueryGetById()
    {
        // Carrega a classe de setor
        $setorGraphqlRequest = new SetorGraphqlRequest();

        // Recupera informações de material por código
        $material = $setorGraphqlRequest->queryGetById(11)->getResults();

        $expected = new stdClass;
        $expected->idsetor = 11;
        $expected->siglasetor = 'FCBS';
        $expected->dataini = '2008-01-01';
        $expected->nomesetor = 'FACULDADE DE CIÊNCIAS BIOLÓGICAS E DA SAÚDE';
        $expected->tiposetor = 'UNIDADE ACAD';
        $expected->datafim = null;
        $expected->fone = null;
        $expected->fax = null;
        $expected->centrocusto = null;
        $expected->obs = null;
        $expected->localizacao = 'DIAMANTINA';
        $expected->paisetor = '0';
        $expected->pairelat = 'UFVJM';
        $expected->idsetorsiape = null;
        $expected->idpaisetor = 26;
        $expected->idcampi = 3;
        $expected->unidadeprotocoladora = null;

        $this->assertEquals($expected, $material);
    }

    /**
     * Lista de setores
     */
    public function testSetorQueryList()
    {
        // Carrega a classe de setor
        $setorGraphqlRequest = new SetorGraphqlRequest();

        $pagination = new ForwardPaginationQuery(3);
        $setores = $setorGraphqlRequest->queryList($pagination)->getResults();

        $this->assertIsArray($setores->edges);
        $this->assertIsObject($setores->pageInfo);
    }
}
