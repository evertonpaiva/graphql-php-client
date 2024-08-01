<?php
namespace GraphqlClient\Tests\Ensino;

use GraphqlClient\GraphqlRequest\Ensino\TurmaGraphqlRequest;
use GraphqlClient\GraphqlQuery\ForwardPaginationQuery;
use GraphqlClient\Tests\GraphqlRequestTest;

use stdClass;

class TurmaGraphqlRequestTest extends GraphqlRequestTest
{
    public function testTurmaQueryGetById()
    {
        // Carrega a classe de grade
        $gradeGraphqlRequest = new TurmaGraphqlRequest();

        // Recupera informações de grade por código
        $turma = $gradeGraphqlRequest->queryGetById(1171882)->getResults();

        $expected = new stdClass;
        $expected->disciplina = 'ICH599';
        $expected->turma = 'D';
        $expected->ano = '2020';
        $expected->semestre = '1';
        $expected->situacao = 'Fechada';
        $expected->idturma = 1171882;

        $this->assertEquals($expected, $turma);
    }

    public function testTurmaQueryList()
    {
        // Carrega a classe de grade
        $turmaGraphqlRequest = new TurmaGraphqlRequest();

        $pagination = new ForwardPaginationQuery(3);
        $turmas = $turmaGraphqlRequest->queryList($pagination)->getResults();

        $this->assertIsArray($turmas->edges);
        $this->assertIsObject($turmas->pageInfo);
    }

    public function testTurmaQueryListByDisciplinaTurmaAnoSemestre()
    {
        // Carrega a classe de turma
        $turmaGraphqlRequest = new TurmaGraphqlRequest();

        $pagination = new ForwardPaginationQuery(3);
        $disciplina = 'COM001';
        $turma = 'S';
        $ano = '2020';
        $semestre = '2';
        $turmas = $turmaGraphqlRequest
            ->queryList($pagination, $disciplina, $turma, $ano, $semestre)
            ->addRelationDisciplina()
            ->getResults();

        $this->assertIsArray($turmas->edges);
        $this->assertIsObject($turmas->pageInfo);
    }
}
