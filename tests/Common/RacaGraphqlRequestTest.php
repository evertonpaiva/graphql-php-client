<?php
namespace GraphqlClient\Tests\Common;

use GraphqlClient\GraphqlRequest\Common\RacaGraphqlRequest;
use GraphqlClient\GraphqlQuery\ForwardPaginationQuery;
use GraphqlClient\Tests\GraphqlRequestTest;
use stdClass;

class RacaGraphqlRequestTest extends GraphqlRequestTest
{
    public function testRacaQueryGetById()
    {
        // Carrega a classe de raça
        $racaGraphqlRequest = new RacaGraphqlRequest();

        // Recupera informações de raca por código
        $raca =
            $racaGraphqlRequest
                ->queryGetById(1)
                ->getResults();

        $expected = new stdClass;
        $expected->idraca = 1;
        $expected->raca = 'Amarela';

        $this->assertEquals($expected, $raca);
    }

    public function testRacaQueryList()
    {
        // Carrega a classe de raca
        $racaGraphqlRequest = new RacaGraphqlRequest();

        $pagination = new ForwardPaginationQuery(3);
        $racas = $racaGraphqlRequest->queryList($pagination)->getResults();

        $this->assertIsArray($racas->edges);
        $this->assertIsObject($racas->pageInfo);
    }
}
