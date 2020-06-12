<?php
namespace GraphqlClient\Tests\Common;

use GraphqlClient\GraphqlRequest\Common\PessoaGraphqlRequest;
use GraphqlClient\GraphqlQuery\ForwardPaginationQuery;
use GraphqlClient\Tests\GraphqlRequestTest;
use stdClass;

class PessoaGraphqlRequestTest extends GraphqlRequestTest
{
    public function testPessoaQueryGetById()
    {
        // Carrega a classe de pessoa
        $pessoaGraphqlRequest = new PessoaGraphqlRequest();

        // Recupera informações de pessoa por código
        $pessoa = $pessoaGraphqlRequest->queryGetById(1)->getResults();

        $expected = new stdClass;
        $expected->idpessoa = 1;
        $expected->nome = 'ADMINISTRADOR DO SISTEMA';
        $expected->cpf = '';
        $expected->sexo = 'M';
        $expected->nomepai = 'ADMIN';
        $expected->containstitucional = 'docker-builder';

        $this->assertEquals($expected, $pessoa);
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
}
