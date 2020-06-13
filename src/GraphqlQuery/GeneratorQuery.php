<?php

namespace GraphqlClient\GraphqlQuery;

use GraphQL\Query;
use stdClass;

class GeneratorQuery
{

    /**
     * Gera query GraphQL para informações simples, de apenas 1 registro (não paginadas)
     * @param $queryName
     * @param $variablesNames
     * @param $arguments
     * @param $fields
     * @return Query
     */
    public static function generateSingleQuery($queryName, $variablesNames, $arguments, $fields, $relations)
    {
        $combinedVariablesValues = [];
        $combinedVariablesNames = $variablesNames;

        foreach ($relations as $r) {
            // Query simples, não possui paginação nem filtros
            if ($r->getType() === RelationType::SINGLE) {
                $fieldsRelation = new Query($r->getRelationName());
                $fieldsRelation->setSelectionSet($r->getRelation()->getFields());
            // Query com paginacao
            } else {
                // Povoando o relation com os valores adicionados no relacionamento
                $relationName = $r->getRelationName();
                $sufix = (ucfirst($relationName));

                $relation = $r->getRelation();

                $relation->setQueryName($r->getRelationName());
                $relation->setPagination($r->getPagination());
                $relation->loadHeaders(false);

                // Adiciona um sufixo no nome das variáveis com o nome do relation
                // para não duplicar com os nomes de variáveis pré-existentes
                $relation->generatePaginatedQuery($sufix);

                $fieldsRelation = $relation->getGql();
                foreach ($relation->getVariablesNames() as $v) {
                    $combinedVariablesNames[] = $v;
                }

                foreach ($relation->getVariablesValues() as $k => $vv) {
                    $combinedVariablesValues[$k] = $vv;
                }
            }
            $fields[] = $fieldsRelation;
        }
        $gql = (new Query($queryName))
            ->setVariables(
                $combinedVariablesNames
            );

        $gql->setArguments($arguments);

        $gql->setSelectionSet($fields);

        $generated = new stdClass();
        $generated->gql = $gql;
        $generated->variablesValues = $combinedVariablesValues;

        return $generated;
    }

    /**
     * Gera query GraphQL para os campos de informações de paginação
     * @param $gql query GraphQL
     * @param $fields vetor de campos
     * @return mixed
     */
    public static function generatePageInfoField($gql, $fields): Query
    {
        $gql->setSelectionSet(
            [
                new PaginatedDataQuery($fields),
                new PageInfoQuery()
            ]
        );

        return $gql;
    }
}
