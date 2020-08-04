<?php
namespace GraphqlClient\Tests\Common;

use GraphqlClient\GraphqlRequest\Common\PessoaGraphqlRequest;
use GraphqlClient\GraphqlQuery\ForwardPaginationQuery;
use GraphqlClient\GraphqlRequest\Ensino\DocenteGraphqlRequest;
use GraphqlClient\Tests\GraphqlRequestTest;
use GraphqlClient\Exception\WrongInstancePaginationException;
use stdClass;

class PessoaGraphqlRequestTest extends GraphqlRequestTest
{
    public function testPessoaQueryGetById()
    {
        // Carrega a classe de pessoa
        $pessoaGraphqlRequest = new PessoaGraphqlRequest();

        // Recupera informações de pessoa por código
        $pessoa =
            $pessoaGraphqlRequest
                ->queryGetById(1)
                ->getResults();

        $expected = new stdClass;
        $expected->idpessoa = 1;
        $expected->nome = 'ADMINISTRADOR DO SISTEMA';
        $expected->cpf = '';
        $expected->sexo = 'M';
        $expected->nomepai = 'ADMIN';
        $expected->containstitucional = 'docker-builder';

        $this->assertEquals($expected, $pessoa);
    }

    public function testPessoaQueryGetByIdRelationDocente()
    {
        // Carrega a classe de pessoa
        $pessoaGraphqlRequest = new PessoaGraphqlRequest();

        // Recupera informações de pessoa por código
        $pessoa =
            $pessoaGraphqlRequest
                ->addRelationDocentes()
                ->queryGetById(5316)
                ->getResults();

        $this->assertIsArray($pessoa->docentes->edges);
        $this->assertIsObject($pessoa->docentes->edges[0]->node);
    }

    public function testWrongInstancePaginationException()
    {
        // Tipo de exceção esperada
        $this->expectException(WrongInstancePaginationException::class);

        // Carrega a classe de pessoa
        $pessoaGraphqlRequest = new PessoaGraphqlRequest();

        // Carrega a classe de docente
        $docenteGraphqlRequest = new DocenteGraphqlRequest();

        // Carrega pessoa, relaciona com docente porém com classe de paginação incorreta
        $pessoaGraphqlRequest
            ->addRelationDocentes($docenteGraphqlRequest, $docenteGraphqlRequest)
            ->queryGetById('COM001')
            ->getResults();
    }

    public function testPessoaQueryList()
    {
        // Carrega a classe de pessoa
        $pessoaGraphqlRequest = new PessoaGraphqlRequest();

        $pagination = new ForwardPaginationQuery(3);
        $pessoas = $pessoaGraphqlRequest->queryList($pagination)->getResults();

        $this->assertIsArray($pessoas->edges);
        $this->assertIsObject($pessoas->pageInfo);
    }

    public function testPessoaQueryListFilterNome()
    {
        // Carrega a classe de pessoa
        $pessoaGraphqlRequest = new PessoaGraphqlRequest();

        $pagination = new ForwardPaginationQuery(3);
        $pessoas = $pessoaGraphqlRequest->queryList($pagination, 'JOÃO')->getResults();

        $this->assertIsArray($pessoas->edges);
        $this->assertIsObject($pessoas->pageInfo);
    }

    public function testPessoaQueryListAddRelations()
    {
        // Carrega a classe de pessoa
        $pessoaGraphqlRequest = new PessoaGraphqlRequest();

        $pagination = new ForwardPaginationQuery(3);
        $pessoas =
            $pessoaGraphqlRequest
                ->addRelationRaca()
                ->addRelationDocentes()
                ->addRelationAlunos()
                ->queryList($pagination)
                ->getResults();

        $this->assertIsArray($pessoas->edges);
        $this->assertIsObject($pessoas->pageInfo);
        $this->assertIsObject($pessoas->edges[0]->node->objRaca);
        $this->assertIsArray($pessoas->edges[0]->node->docentes->edges);
        $this->assertIsArray($pessoas->edges[0]->node->alunos->edges);
    }
}
