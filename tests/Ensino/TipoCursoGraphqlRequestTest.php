<?php
namespace GraphqlClient\Tests\Ensino;

use GraphqlClient\GraphqlRequest\Ensino\TipoCursoGraphqlRequest;
use GraphqlClient\GraphqlQuery\ForwardPaginationQuery;
use GraphqlClient\Tests\GraphqlRequestTest;
use stdClass;

class TipoCursoGraphqlRequestTest extends GraphqlRequestTest
{
    public function testTipoCursoQueryGetById()
    {
        // Carrega a classe de tipo de curso
        $tipoCursoGraphqlRequest = new TipoCursoGraphqlRequest();

        // Recupera informações de situação de aluno por código
        $tipoCurso = $tipoCursoGraphqlRequest->queryGetById('01')->getResults();

        $expected = new stdClass;
        $expected->idtipocurso = '01';
        $expected->tipocurso = 'GRADUAÇÃO';

        $this->assertEquals($expected, $tipoCurso);
    }

    public function testTipoCursoQueryList()
    {
        // Carrega a classe de tipo de curso
        $tipoCursoGraphqlRequest = new TipoCursoGraphqlRequest();

        $pagination = new ForwardPaginationQuery(2);
        $tipoCurso = $tipoCursoGraphqlRequest->queryList($pagination)->getResults();

        $this->assertIsArray($tipoCurso->edges);
        $this->assertIsObject($tipoCurso->pageInfo);
    }
}
