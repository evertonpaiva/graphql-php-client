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
        $expected->cpf_limpo = null;
        $expected->sexo = 'M';
        $expected->nomepai = 'ADMIN';
        $expected->containstitucional = 'docker-builder';
        $expected->telefone = '32175486';
        $expected->celular = '99058333';

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
        // Dividindo os testes em 2 requisições para evitar ultrapassar
        // limite de complexidade de consulta

        // Carrega a classe de pessoa
        $pessoaGraphqlRequest1 = new PessoaGraphqlRequest();

        $pagination = new ForwardPaginationQuery(3);
        $pessoas1 =
            $pessoaGraphqlRequest1
                ->addRelationRaca()
                ->addRelationDocentes()
                ->addRelationAlunos()
                ->queryList($pagination)
                ->getResults();

        $this->assertIsArray($pessoas1->edges);
        $this->assertIsObject($pessoas1->pageInfo);
        $this->assertIsObject($pessoas1->edges[0]->node->objRaca);
        $this->assertIsArray($pessoas1->edges[0]->node->docentes->edges);
        $this->assertIsArray($pessoas1->edges[0]->node->alunos->edges);

        // Carrega a classe de pessoa
        $pessoaGraphqlRequest2 = new PessoaGraphqlRequest();
        $pessoas2 =
            $pessoaGraphqlRequest2
                ->addRelationServidores()
                ->queryList($pagination)
                ->getResults();
        $this->assertIsArray($pessoas2->edges);
        $this->assertIsObject($pessoas2->pageInfo);
        $this->assertIsArray($pessoas2->edges[0]->node->servidores->edges);
    }
}
