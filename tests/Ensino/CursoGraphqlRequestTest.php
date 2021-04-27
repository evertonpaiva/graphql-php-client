<?php
namespace GraphqlClient\Tests\Ensino;

use GraphqlClient\Exception\WrongInstancePaginationException;
use GraphqlClient\GraphqlRequest\Ensino\CursoGraphqlRequest;
use GraphqlClient\GraphqlQuery\ForwardPaginationQuery;
use GraphqlClient\Tests\GraphqlRequestTest;
use stdClass;

class CursoGraphqlRequestTest extends GraphqlRequestTest
{
    /**
     * Teste de curso por código
     */
    public function testCursoQueryGetById()
    {
        // Carrega a classe de curso
        $cursoGraphqlRequest = new CursoGraphqlRequest();

        // Recupera informações de curso por código
        $curso = $cursoGraphqlRequest->queryGetById('SIN')->getResults();

        $expected = new stdClass;
        $expected->curso = 'SIN';
        $expected->nome = 'SISTEMAS DE INFORMAÇÃO';

        $this->assertEquals($expected, $curso);
    }

    /**
     * Teste de lista de cursos
     * @throws WrongInstancePaginationException
     */
    public function testCursoQueryList()
    {
        // Carrega a classe de curso
        $cursoGraphqlRequest = new CursoGraphqlRequest();

        $pagination = new ForwardPaginationQuery(3);
        $cursos = $cursoGraphqlRequest->queryList($pagination)->getResults();

        $this->assertIsArray($cursos->edges);
        $this->assertIsObject($cursos->pageInfo);
    }

    /**
     * Teste de lista de cursos filtrando por nome
     * @throws WrongInstancePaginationException
     */
    public function testCursoQueryListFilterNome()
    {
        // Carrega a classe de curso
        $cursoGraphqlRequest = new CursoGraphqlRequest();

        $pagination = new ForwardPaginationQuery(3);
        $cursos = $cursoGraphqlRequest->queryList($pagination, 'SISTEMAS')->getResults();

        $this->assertIsArray($cursos->edges);
        $this->assertIsObject($cursos->pageInfo);
    }

    public function testCursoQueryListAddRelations()
    {
        // Carrega a classe de curso
        $cursoGraphqlRequest = new CursoGraphqlRequest();

        $pagination = new ForwardPaginationQuery(3);
        $cursos =
            $cursoGraphqlRequest
                ->addRelationModalidade()
                ->addRelationTipoCurso()
                ->queryList($pagination)
                ->getResults();

        $this->assertIsArray($cursos->edges);
        $this->assertIsObject($cursos->pageInfo);
        $this->assertIsObject($cursos->edges[0]->node->objModalidade);
        $this->assertIsObject($cursos->edges[0]->node->objTipoCurso);
    }
}
