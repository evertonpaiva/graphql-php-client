<?php
namespace GraphqlClient\Tests\Ensino;

use GraphqlClient\GraphqlRequest\Ensino\AlunoGraphqlRequest;
use GraphqlClient\GraphqlQuery\ForwardPaginationQuery;
use GraphqlClient\Tests\GraphqlRequestTest;
use stdClass;

class AlunoGraphqlRequestTest extends GraphqlRequestTest
{
    public function testAlunoQueryGetById()
    {
        // Carrega a classe de aluno
        $alunoGraphqlRequest = new AlunoGraphqlRequest();

        // Recupera informações de aluno por código
        $aluno = $alunoGraphqlRequest->queryGetById('1201219602')->getResults();

        $expected = new stdClass;
        $expected->matricula = '1201219602';
        $expected->anoingresso = '2012';
        $expected->semingresso = '1';
        $expected->idpessoa = 725225;
        $expected->cra = '';
        $expected->percentualconclusao = '0,00';

        $this->assertEquals($expected, $aluno);
    }

    public function testAlunoQueryList()
    {
        // Carrega a classe de aluno
        $alunoGraphqlRequest = new AlunoGraphqlRequest();

        $pagination = new ForwardPaginationQuery(3);
        $alunos = $alunoGraphqlRequest->queryList($pagination)->getResults();

        $this->assertIsArray($alunos->edges);
        $this->assertIsObject($alunos->pageInfo);
    }

    public function testAlunoQueryListAddRelations()
    {
        // Carrega a classe de aluno
        $alunoGraphqlRequest = new AlunoGraphqlRequest();

        $pagination = new ForwardPaginationQuery(3);
        $alunos =
            $alunoGraphqlRequest
                ->addRelationPessoa()
                ->addRelationPrograma()
                ->addRelationSituacao()
                ->queryList($pagination)
                ->getResults();

        $this->assertIsArray($alunos->edges);
        $this->assertIsObject($alunos->pageInfo);
        $this->assertIsObject($alunos->edges[0]->node->objPessoa);
        $this->assertIsObject($alunos->edges[0]->node->objPrograma);
        $this->assertIsObject($alunos->edges[0]->node->objSituacao);
    }
}
