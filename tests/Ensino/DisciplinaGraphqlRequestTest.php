<?php
namespace GraphqlClient\Tests\Ensino;

use GraphqlClient\Exception\WrongInstanceRelationException;
use GraphqlClient\GraphqlQuery\BackwardPaginationQuery;
use GraphqlClient\GraphqlQuery\ForwardPaginationQuery;
use GraphqlClient\Tests\GraphqlRequestTest;
use GraphqlClient\GraphqlRequest\Ensino\DisciplinaGraphqlRequest;
use stdClass;

class DisciplinaGraphqlRequestTest extends GraphqlRequestTest
{
    /**
     * Recupera disciplinas por código
     */
    public function testDisciplinaQueryGetById()
    {
        // Carrega a classe de disciplina
        $disciplinaGraphqlRequest = new DisciplinaGraphqlRequest();

        // Recupera informações de disciplina por código
        $disciplina = $disciplinaGraphqlRequest->queryGetById('COM001')->getResults();

        $expected = new stdClass;
        $expected->disciplina = 'COM001';
        $expected->nome = 'ALGORITMOS E ESTRUTURA DE DADOS I';
        $expected->iddepto = 'DCO';
        $expected->creditostotal = 5;
        $expected->cargahorariatotal = 75;

        $this->assertEquals($expected, $disciplina);
    }

    public function testDisciplinaQueryGetByIdDepartamento()
    {
        // Carrega a classe de disciplina
        $disciplinaGraphqlRequest = new DisciplinaGraphqlRequest();

        // Recupera informações de disciplina por código
        $disciplina =
            $disciplinaGraphqlRequest
                ->addRelationDepartamento()
                ->queryGetById('COM001')
                ->getResults();

        $expected = new stdClass;
        $expected->disciplina = 'COM001';
        $expected->nome = 'ALGORITMOS E ESTRUTURA DE DADOS I';
        $expected->iddepto = 'DCO';
        $expected->creditostotal = 5;
        $expected->cargahorariatotal = 75;
        $expected->objDepartamento = new stdClass();
        $expected->objDepartamento->iddepto = 'DCO';
        $expected->objDepartamento->depto = 'COM';
        $expected->objDepartamento->nome = 'DEPARTAMENTO DE COMPUTAÇÃO';

        $this->assertEquals($expected, $disciplina);
    }

    public function testWrongRelationException(){
        // Tipo de exceção esperada
        $this->expectException(WrongInstanceRelationException::class);

        // Carrega a classe de disciplina
        $disciplinaGraphqlRequest = new DisciplinaGraphqlRequest();

        // Carrega disciplina com relacionamento incorreto
        $disciplinaGraphqlRequest
            ->addRelationDepartamento($disciplinaGraphqlRequest)
            ->queryGetById('COM001')
            ->getResults();
    }

    /**
     * Lista disciplinas
     */
    public function testDisciplinaQueryList()
    {
        // Carrega a classe de disciplina
        $disciplinaGraphqlRequest = new DisciplinaGraphqlRequest();

        $pagination = new ForwardPaginationQuery(3);
        $disciplinas = $disciplinaGraphqlRequest->queryList($pagination)->getResults();

        $this->assertIsArray($disciplinas->edges);
        $this->assertIsObject($disciplinas->pageInfo);
    }

    /**
     * Lista disciplinas com paginação para frente
     */
    public function testDisciplinaQueryListCursor()
    {
        // Carrega a classe de disciplina
        $disciplinaGraphqlRequest = new DisciplinaGraphqlRequest();

        $pagination = new ForwardPaginationQuery(3, 'WyIxMzY4MyAgICAgICAgICAgICAgICJd');
        $disciplinas = $disciplinaGraphqlRequest->queryList($pagination)->getResults();

        $this->assertIsArray($disciplinas->edges);
        $this->assertIsObject($disciplinas->pageInfo);
    }

    /**
     * Lista disciplinas com paginação para trás
     */
    public function testDisciplinaQueryListBackwardPagination()
    {
        // Carrega a classe de disciplina
        $disciplinaGraphqlRequest = new DisciplinaGraphqlRequest();

        $pagination = new BackwardPaginationQuery(1);
        $disciplinas = $disciplinaGraphqlRequest->queryList($pagination)->getResults();

        $this->assertIsArray($disciplinas->edges);
        $this->assertIsObject($disciplinas->pageInfo);
    }
}
