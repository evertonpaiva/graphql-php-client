<?php
namespace GraphqlClient\Tests\Ensino;

use GraphqlClient\GraphqlRequest\Ensino\GradeGraphqlRequest;
use GraphqlClient\GraphqlQuery\ForwardPaginationQuery;
use GraphqlClient\Tests\GraphqlRequestTest;

use stdClass;

class GradeGraphqlRequestTest extends GraphqlRequestTest
{
    public function testGradeQueryGetById()
    {
        // Carrega a classe de grade
        $gradeGraphqlRequest = new GradeGraphqlRequest();

        // Recupera informações de grade por código
        $aluno = $gradeGraphqlRequest->queryGetById('SIN', '20071', 'MAT001')->getResults();

        $expected = new stdClass;
        $expected->curso = 'SIN';
        $expected->curriculo = '20071';
        $expected->disciplina = 'MAT001';
        $expected->periodo = '1';

        $this->assertEquals($expected, $aluno);
    }

    public function testGradeQueryList()
    {
        // Carrega a classe de grade
        $gradeGraphqlRequest = new GradeGraphqlRequest();

        $pagination = new ForwardPaginationQuery(3);
        $grades = $gradeGraphqlRequest->queryList($pagination)->getResults();

        $this->assertIsArray($grades->edges);
        $this->assertIsObject($grades->pageInfo);
    }

    public function testGradeQueryListByCursoCurriculo()
    {
        // Carrega a classe de grade
        $gradeGraphqlRequest = new GradeGraphqlRequest();

        $pagination = new ForwardPaginationQuery(3);
        $curso = 'SIN';
        $curriculo = '20071';
        $grades = $gradeGraphqlRequest
            ->queryList($pagination, $curso, $curriculo)
            ->addRelationCurso()
            ->addRelationDisciplina()
            ->addRelationCurriculo()
            ->getResults();

        $this->assertIsArray($grades->edges);
        $this->assertIsObject($grades->pageInfo);
    }
}
