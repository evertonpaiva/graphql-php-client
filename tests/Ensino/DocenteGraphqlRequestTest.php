<?php
namespace GraphqlClient\Tests\Ensino;

use GraphqlClient\GraphqlRequest\Ensino\DocenteGraphqlRequest;
use GraphqlClient\GraphqlQuery\ForwardPaginationQuery;
use GraphqlClient\Tests\GraphqlRequestTest;
use stdClass;

class DocenteGraphqlRequestTest extends GraphqlRequestTest
{
    public function testDocenteQueryGetById()
    {
        // Carrega a classe de docente
        $docenteGraphqlRequest = new DocenteGraphqlRequest();

        // Recupera informações de docente por código
        $docente = $docenteGraphqlRequest->queryGetById(15339)->getResults();

        $expected = new stdClass;
        $expected->iddocente = 15339;
        $expected->idpessoa = 5316;
        $expected->idvinc = '0390037';
        $expected->situacao = 'Ativo';
        $expected->iddepto = 'DOD';

        $this->assertEquals($expected, $docente);
    }

    public function testDocenteQueryList()
    {
        // Carrega a classe de docente
        $docenteGraphqlRequest = new DocenteGraphqlRequest();

        $pagination = new ForwardPaginationQuery(3);
        $docentes = $docenteGraphqlRequest->queryList($pagination)->getResults();

        $this->assertIsArray($docentes->edges);
        $this->assertIsObject($docentes->pageInfo);
    }

    public function testDocenteQueryListAddRelations()
    {
        // Carrega a classe de docente
        $docenteGraphqlRequest = new DocenteGraphqlRequest();

        $pagination = new ForwardPaginationQuery(3);
        $docentes =
            $docenteGraphqlRequest
                ->addRelationDepartamento()
                ->queryList($pagination)
                ->getResults();

        $this->assertIsArray($docentes->edges);
        $this->assertIsObject($docentes->pageInfo);
        $this->assertIsObject($docentes->edges[0]->node->objDepartamento);
    }
}
