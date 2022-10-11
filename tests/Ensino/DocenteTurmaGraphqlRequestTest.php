<?php
namespace GraphqlClient\Tests\Ensino;

use GraphqlClient\GraphqlRequest\Ensino\DocenteTurmaGraphqlRequest;
use GraphqlClient\GraphqlQuery\ForwardPaginationQuery;
use GraphqlClient\Tests\GraphqlRequestTest;

use stdClass;

class DocenteTurmaGraphqlRequestTest extends GraphqlRequestTest
{
    public function testDocenteTurmaQueryGetById()
    {
        // Carrega a classe de docentes por turma
        $docenteTurmaGraphqlRequest = new DocenteTurmaGraphqlRequest();

        // Recupera informações de docenteTurma por código
        $docenteTurma = $docenteTurmaGraphqlRequest->queryGetById(1177745, 26870)->getResults();

        $expected = new stdClass;
        $expected->idturma = 1177745;
        $expected->iddocente = 26870;
        $expected->horasaula = 75;

        $this->assertEquals($expected, $docenteTurma);
    }

    public function testDocenteTurmaQueryList()
    {
        // Carrega a classe de docenteTurma
        $docenteTurmaGraphqlRequest = new DocenteTurmaGraphqlRequest();

        $pagination = new ForwardPaginationQuery(3);
        $docenteTurmas = $docenteTurmaGraphqlRequest->queryList($pagination)->getResults();

        $this->assertIsArray($docenteTurmas->edges);
        $this->assertIsObject($docenteTurmas->pageInfo);
    }

    public function testDocenteTurmaQueryListByDisciplinaTurmaAnoSemestre()
    {
        // Carrega a classe de docenteTurma
        $docenteTurmaGraphqlRequest = new DocenteTurmaGraphqlRequest();

        $pagination = new ForwardPaginationQuery(3);
        $idturma = 838697;
        $iddocente = 15726;
        $docenteTurmas = $docenteTurmaGraphqlRequest
            ->queryList($pagination, $idturma, $iddocente)
            ->addRelationDocente()
            ->addRelationTurma()
            ->getResults();

        $this->assertIsArray($docenteTurmas->edges);
        $this->assertIsObject($docenteTurmas->pageInfo);
    }
}
