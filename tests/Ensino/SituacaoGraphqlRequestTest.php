<?php
namespace GraphqlClient\Tests\Ensino;

use GraphqlClient\GraphqlRequest\Ensino\SituacaoGraphqlRequest;
use GraphqlClient\GraphqlQuery\ForwardPaginationQuery;
use GraphqlClient\Tests\GraphqlRequestTest;
use stdClass;

class SituacaoGraphqlRequestTest extends GraphqlRequestTest
{
    public function testSituacaoQueryGetById()
    {
        // Carrega a classe de situação de aluno
        $situacaoGraphqlRequest = new SituacaoGraphqlRequest();

        // Recupera informações de situação de aluno por código
        $situacao = $situacaoGraphqlRequest->queryGetById('02')->getResults();

        $expected = new stdClass;
        $expected->idsituacao = '02';
        $expected->situacao = 'ATIVO';

        $this->assertEquals($expected, $situacao);
    }

    public function testSituacaoQueryList()
    {
        // Carrega a classe de situação de aluno
        $situacaoGraphqlRequest = new SituacaoGraphqlRequest();

        $pagination = new ForwardPaginationQuery(3);
        $situacoes = $situacaoGraphqlRequest->queryList($pagination)->getResults();

        $this->assertIsArray($situacoes->edges);
        $this->assertIsObject($situacoes->pageInfo);
    }
}
