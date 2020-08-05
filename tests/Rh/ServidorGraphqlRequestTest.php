<?php
namespace GraphqlClient\Tests\Rh;

use GraphqlClient\GraphqlRequest\Rh\ServidorGraphqlRequest;
use GraphqlClient\GraphqlQuery\ForwardPaginationQuery;
use GraphqlClient\Tests\GraphqlRequestTest;
use stdClass;

class ServidorGraphqlRequestTest extends GraphqlRequestTest
{
    public function testServidorQueryGetById()
    {
        // Carrega a classe de servidor
        $servidorGraphqlRequest = new ServidorGraphqlRequest();

        // Recupera informações de aluno por código
        $aluno = $servidorGraphqlRequest->queryGetById('016702747')->getResults();

        $expected = new stdClass;
        $expected->idfuncionario = '016702747';
        $expected->idvinculo = '1670274';
        $expected->idpessoa = 656582;
        $expected->idsetor = 1430;
        $expected->cargo = 'ANALISTA DE TEC DA INFORMAÇÃO ';

        $this->assertEquals($expected, $aluno);
    }

    public function testServidorQueryList()
    {
        // Carrega a classe de servidor
        $servidorGraphqlRequest = new ServidorGraphqlRequest();

        $pagination = new ForwardPaginationQuery(3);
        $servidores = $servidorGraphqlRequest->queryList($pagination)->getResults();

        $this->assertIsArray($servidores->edges);
        $this->assertIsObject($servidores->pageInfo);
    }

    public function testServidorQueryListAddRelations()
    {
        // Carrega a classe de servidor
        $servidorGraphqlRequest = new ServidorGraphqlRequest();

        $pagination = new ForwardPaginationQuery(3);
        $servidores =
            $servidorGraphqlRequest
                ->addRelationPessoa()
                ->addRelationSetor()
                ->queryList($pagination)
                ->getResults();

        $this->assertIsArray($servidores->edges);
        $this->assertIsObject($servidores->pageInfo);
        $this->assertIsObject($servidores->edges[0]->node->objPessoa);
        $this->assertIsObject($servidores->edges[0]->node->objSetor);
    }
}
