<?php
namespace GraphqlClient\Tests\Ensino;

use GraphqlClient\GraphqlRequest\Ensino\MatriculaGraphqlRequest;
use GraphqlClient\GraphqlQuery\ForwardPaginationQuery;
use GraphqlClient\Tests\GraphqlRequestTest;

use stdClass;

class MatriculaGraphqlRequestTest extends GraphqlRequestTest
{
    public function testMatriculaQueryGetById()
    {
        // Carrega a classe de matricula
        $matriculaGraphqlRequest = new MatriculaGraphqlRequest();

        // Recupera informações de grade por código
        $turma = $matriculaGraphqlRequest->queryGetById(1206556, '20242022014')->getResults();

        $expected = new stdClass;
        $expected->matricula = '20242022014';
        $expected->idturma = '1206556';
        $expected->nota = '';
        $expected->segundaepoca = '';
        $expected->freq = '';
        $expected->situacao = 'Aberta';
        $expected->tipo = 'OBRIGATÓRIA';

        $this->assertEquals($expected, $turma);
    }

    public function testMatriculaQueryList()
    {
        // Carrega a classe de matricula
        $matriculaGraphqlRequest = new MatriculaGraphqlRequest();

        $pagination = new ForwardPaginationQuery(3);
        $matriculas = $matriculaGraphqlRequest->queryList($pagination)->getResults();

        $this->assertIsArray($matriculas->edges);
        $this->assertIsObject($matriculas->pageInfo);
    }

    public function testMatriculaQueryListByMatricula()
    {
        // Carrega a classe de matricula
        $matriculaGraphqlRequest = new MatriculaGraphqlRequest();
        $pagination = new ForwardPaginationQuery(3);
        $matricula = '20241038012';
        $matriculas = $matriculaGraphqlRequest
                ->addRelationAluno()
                ->addRelationTurma()
                ->queryList($pagination, $matricula)
                ->getResults();

        $this->assertIsArray($matriculas->edges);
        $this->assertIsObject($matriculas->pageInfo);
        $this->assertIsObject($matriculas->edges[0]->node->objAluno);
        $this->assertIsObject($matriculas->edges[0]->node->objTurma);
    }
}
