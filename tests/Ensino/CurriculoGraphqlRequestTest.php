<?php
namespace GraphqlClient\Tests\Ensino;

use GraphqlClient\GraphqlRequest\Ensino\CurriculoGraphqlRequest;
use GraphqlClient\GraphqlQuery\ForwardPaginationQuery;
use GraphqlClient\Tests\GraphqlRequestTest;

use stdClass;

class CurriculoGraphqlRequestTest extends GraphqlRequestTest
{
    public function testCurriculoQueryGetById()
    {
        // Carrega a classe de curriculo
        $curriculoGraphqlRequest = new CurriculoGraphqlRequest();

        // Recupera informações de curriculo por código
        $aluno = $curriculoGraphqlRequest->queryGetById('SIN', '20071')->getResults();

        $expected = new stdClass;
        $expected->curso = 'SIN';
        $expected->curriculo = '20071';
        $expected->anoini = '2007';
        $expected->semini = '1';

        $this->assertEquals($expected, $aluno);
    }

    public function testCurriculoQueryList()
    {
        // Carrega a classe de curriculo
        $curriculoGraphqlRequest = new CurriculoGraphqlRequest();

        $pagination = new ForwardPaginationQuery(3);
        $curriculos = $curriculoGraphqlRequest->queryList($pagination)->getResults();

        $this->assertIsArray($curriculos->edges);
        $this->assertIsObject($curriculos->pageInfo);
    }
}
