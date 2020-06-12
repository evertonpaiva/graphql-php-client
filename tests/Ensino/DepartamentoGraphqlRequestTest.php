<?php
namespace GraphqlClient\Tests\Ensino;

use GraphqlClient\GraphqlRequest\Ensino\DepartamentoGraphqlRequest;
use GraphqlClient\GraphqlQuery\ForwardPaginationQuery;
use GraphqlClient\Tests\GraphqlRequestTest;
use GraphqlClient\GraphqlRequest\Ensino\DisciplinaGraphqlRequest;
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

    public function testDisciplinaQueryList()
    {
        // Carrega a classe de disciplina
        $departamentoGraphqlRequest = new DisciplinaGraphqlRequest();

        $pagination = new ForwardPaginationQuery(3);
        $disciplinas = $departamentoGraphqlRequest->queryList($pagination)->getResults();

        $this->assertIsArray($disciplinas->edges);
        $this->assertIsObject($disciplinas->pageInfo);
    }
}
