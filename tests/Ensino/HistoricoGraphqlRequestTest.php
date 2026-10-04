<?php
namespace GraphqlClient\Tests\Ensino;

use GraphqlClient\GraphqlRequest\Ensino\HistoricoGraphqlRequest;
use GraphqlClient\GraphqlQuery\ForwardPaginationQuery;
use GraphqlClient\Tests\GraphqlRequestTest;

use stdClass;

class HistoricoGraphqlRequestTest extends GraphqlRequestTest
{
    public function testHistoricoQueryGetById()
    {
        // Carrega a classe de historico
        $historicoGraphqlRequest = new HistoricoGraphqlRequest();

        // Recupera informações de histórico por aluno, disciplina, ano e semestre
        $historico = $historicoGraphqlRequest->queryGetById('20221016034', 'COM001', '2024', '1')->getResults();

        $expected = new stdClass();
        $expected->matricula = '20221016034';
        $expected->disciplina = 'COM001';
        $expected->ano = '2024';
        $expected->semestre = '1';
        $expected->idconceito = null;
        $expected->nota = '0';
        $expected->frequencia = 'Insuficiente';
        $expected->segundaepoca = '';
        $expected->idturma = 1200806;
        $expected->tipo = 'OBRIGATÓRIA';

        $this->assertEquals($expected, $historico);
    }

    public function testHistoricoQueryList()
    {
        // Carrega a classe de historico
        $historicoGraphqlRequest = new HistoricoGraphqlRequest();

        $pagination = new ForwardPaginationQuery(3);
        $matricula = '20221016034';
        $historicos = $historicoGraphqlRequest->queryList($pagination, $matricula)->getResults();

        $this->assertIsArray($historicos->edges);
        $this->assertIsObject($historicos->pageInfo);
    }

    public function testHistoricoQueryListByMatriculaWithRelations()
    {
        // Carrega a classe de historico
        $historicoGraphqlRequest = new HistoricoGraphqlRequest();
        $pagination = new ForwardPaginationQuery(3);
        $matricula = '20221016034';
        $historicos = $historicoGraphqlRequest
                ->addRelationAluno()
                ->addRelationTurma()
                ->addRelationDisciplina()
                ->queryList($pagination, $matricula)
                ->getResults();

        $this->assertIsArray($historicos->edges);
        $this->assertIsObject($historicos->pageInfo);
        $this->assertIsObject($historicos->edges[0]->node->objAluno);
        $this->assertIsObject($historicos->edges[0]->node->objTurma);
        $this->assertIsObject($historicos->edges[0]->node->objDisciplina);
    }
}
