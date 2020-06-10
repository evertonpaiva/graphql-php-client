<?php
namespace GraphqlClient\Tests\Ensino;

use GraphqlClient\GraphqlRequest\BackwardPaginationQuery;
use GraphqlClient\GraphqlRequest\ForwardPaginationQuery;
use GraphqlClient\Tests\GraphqlRequestTest;
use GraphqlClient\GraphqlRequest\Ensino\DisciplinaGraphqlRequest;
use stdClass;

class EnsinoDisciplinaGraphqlRequestTest extends GraphqlRequestTest
{
    public function testDisciplinaQueryGetById()
    {
        // Carrega a classe de disciplina
        $disciplinaGrapqhlRequest = new DisciplinaGraphqlRequest();

        // Recupera informações de disciplina por código
        $disciplina = $disciplinaGrapqhlRequest->queryGetById('COM001')->getResults();

        $expected = new stdClass;
        $expected->disciplina = 'COM001';
        $expected->nome = 'ALGORITMOS E ESTRUTURA DE DADOS I';
        $expected->iddepto = 'DCO';
        $expected->creditostotal = 5;
        $expected->cargahorariatotal = 75;

        $this->assertEquals($expected, $disciplina);
    }

    public function testDisciplinaQueryList()
    {
        // Carrega a classe de disciplina
        $disciplinaGrapqhlRequest = new DisciplinaGraphqlRequest();

        $pagination = new ForwardPaginationQuery(3);
        $disciplinas = $disciplinaGrapqhlRequest->queryList($pagination)->getResults();

        $this->assertIsArray($disciplinas->edges);
        $this->assertIsObject($disciplinas->pageInfo);
    }

    public function testDisciplinaQueryListCursor()
    {
        // Carrega a classe de disciplina
        $disciplinaGrapqhlRequest = new DisciplinaGraphqlRequest();

        $pagination = new ForwardPaginationQuery(3, 'WyIxMzY4MyAgICAgICAgICAgICAgICJd');
        $disciplinas = $disciplinaGrapqhlRequest->queryList($pagination)->getResults();

        $this->assertIsArray($disciplinas->edges);
        $this->assertIsObject($disciplinas->pageInfo);
    }

    public function testDisciplinaQueryListBackwardPagination()
    {
        // Carrega a classe de disciplina
        $disciplinaGrapqhlRequest = new DisciplinaGraphqlRequest();

        $pagination = new BackwardPaginationQuery(1);
        $disciplinas = $disciplinaGrapqhlRequest->queryList($pagination)->getResults();

        $this->assertIsArray($disciplinas->edges);
        $this->assertIsObject($disciplinas->pageInfo);
    }
}
