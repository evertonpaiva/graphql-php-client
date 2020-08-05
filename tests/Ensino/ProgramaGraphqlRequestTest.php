<?php
namespace GraphqlClient\Tests\Ensino;

use GraphqlClient\GraphqlRequest\Ensino\ProgramaGraphqlRequest;
use GraphqlClient\GraphqlQuery\ForwardPaginationQuery;
use GraphqlClient\Tests\GraphqlRequestTest;
use stdClass;

class ProgramaGraphqlRequestTest extends GraphqlRequestTest
{
    public function testProgramaQueryGetById()
    {
        // Carrega a classe de programa
        $programaGraphqlRequest = new ProgramaGraphqlRequest();

        // Recupera informações de programa por código
        $aluno = $programaGraphqlRequest->queryGetById('1201219602')->getResults();

        $expected = new stdClass;
        $expected->matricula = '1201219602';
        $expected->curso = '99A';
        $expected->curriculo = '20091';

        $this->assertEquals($expected, $aluno);
    }

    public function testProgramaQueryList()
    {
        // Carrega a classe de programa
        $programaGraphqlRequest = new ProgramaGraphqlRequest();

        $pagination = new ForwardPaginationQuery(3);
        $programas = $programaGraphqlRequest->queryList($pagination)->getResults();

        $this->assertIsArray($programas->edges);
        $this->assertIsObject($programas->pageInfo);
    }

    public function testProgramaQueryListAddRelations()
    {
        // Carrega a classe de programa
        $programaGraphqlRequest = new ProgramaGraphqlRequest();

        $pagination = new ForwardPaginationQuery(3);
        $programas =
            $programaGraphqlRequest
                ->addRelationCurso()
                ->queryList($pagination)
                ->getResults();

        $this->assertIsArray($programas->edges);
        $this->assertIsObject($programas->pageInfo);
        $this->assertIsObject($programas->edges[0]->node->objCurso);
    }
}
