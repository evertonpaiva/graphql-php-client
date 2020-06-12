<?php

namespace GraphqlClient\GraphqlQuery;

use GraphQL\Query;

class QueryGenerator
{

    /**
     * Gera query GraphQL para informações simples, de apenas 1 registro (não paginadas)
     * @param $queryName
     * @param $variablesNames
     * @param $arguments
     * @param $fields
     * @return Query
     */
    public static function generateSingleQuery($queryName, $variablesNames, $arguments, $fields): Query
    {
        $gql = (new Query($queryName))
            ->setVariables(
                $variablesNames
            );

        $gql->setArguments($arguments);

        $gql->setSelectionSet($fields);

        return $gql;
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
