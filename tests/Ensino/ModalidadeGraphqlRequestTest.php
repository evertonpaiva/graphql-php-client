<?php
namespace GraphqlClient\Tests\Ensino;

use GraphqlClient\GraphqlRequest\Ensino\ModalidadeGraphqlRequest;
use GraphqlClient\GraphqlQuery\ForwardPaginationQuery;
use GraphqlClient\Tests\GraphqlRequestTest;
use stdClass;

class ModalidadeGraphqlRequestTest extends GraphqlRequestTest
{
    public function testModalidadeQueryGetById()
    {
        // Carrega a classe de modalidade de curso
        $modalidadeGraphqlRequest = new ModalidadeGraphqlRequest();

        // Recupera informações de modalidade de curso por código
        $modalidade = $modalidadeGraphqlRequest->queryGetById(1)->getResults();

        $expected = new stdClass;
        $expected->idmodalidade = 1;
        $expected->modalidade = 'PRESENCIAL';

        $this->assertEquals($expected, $modalidade);
    }

    public function testModalidadeQueryList()
    {
        // Carrega a classe de modalidade de curso
        $modalidadeGraphqlRequest = new ModalidadeGraphqlRequest();

        $pagination = new ForwardPaginationQuery(3);
        $modalidades = $modalidadeGraphqlRequest->queryList($pagination)->getResults();

        $this->assertIsArray($modalidades->edges);
        $this->assertIsObject($modalidades->pageInfo);
    }
}
