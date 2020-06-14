<?php
namespace GraphqlClient\Tests\Ensino;

use GraphqlClient\GraphqlRequest\Ensino\DepartamentoGraphqlRequest;
use GraphqlClient\GraphqlQuery\ForwardPaginationQuery;
use GraphqlClient\Tests\GraphqlRequestTest;
use stdClass;

class DepartamentoGraphqlRequestTest extends GraphqlRequestTest
{
    public function testDepartamentoQueryGetById()
    {
        // Carrega a classe de departamento
        $departamentoGraphqlRequest = new DepartamentoGraphqlRequest();

        // Recupera informações de disciplina por código
        $departamento = $departamentoGraphqlRequest->queryGetById('DCO')->getResults();

        $expected = new stdClass;
        $expected->iddepto = 'DCO';
        $expected->depto = 'COM';
        $expected->nome = 'DEPARTAMENTO DE COMPUTAÇÃO';

        $this->assertEquals($expected, $departamento);
    }

    public function testDepartamentoQueryList()
    {
        // Carrega a classe de disciplina
        $departamentoGraphqlRequest = new DepartamentoGraphqlRequest();

        $pagination = new ForwardPaginationQuery(3);
        $departamentos = $departamentoGraphqlRequest->queryList($pagination)->getResults();

        $this->assertIsArray($departamentos->edges);
        $this->assertIsObject($departamentos->pageInfo);
    }
}
