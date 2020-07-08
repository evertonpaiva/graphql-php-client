<?php

namespace GraphqlClient\GraphqlRequest;

/**
 * Class ConfigRequest
 * Armazena as configurações globais de GraphqlRequest
 * @package GraphqlClient\GraphqlRequest
 */
abstract class ConfigRequest
{
    /**
     * Variável de ambiente para o ID da Aplicação
     */
    const APP_ID_ENV = 'GRAPHQL_APP_ID';

    /**
     * Variável de ambiente para o KEY da Aplicação
     */
    const APP_KEY_ENV = 'GRAPHQL_APP_KEY';

    /**
     * Variável de ambiente para a URL do servidor GraphQL do ambiente local
     */
    const GRAPHQL_URL_LOCAL = 'http://192.168.1.106:30000/';

    /**
     * Variável de ambiente para a URL do servidor GraphQL do ambiente de testes
     */
    const GRAPHQL_URL_TESTE = 'https://micro-teste.dds.ufvjm.edu.br/';

    /**
     * Variável de ambiente para a URL do servidor GraphQL do ambiente de produção
     */
    const GRAPHQL_URL_PROD = 'https://micro.dds.ufvjm.edu.br/';

    /**
     * Variável de ambiente para a URL do servidor GraphQL
     */
    const GRAPHQL_ENVNAME_ENV = 'GRAPHQL_ENVNAME';

    /**
     * Nome do cabeçalho da Aplicação
     */
    const APP_HEADER_NAME = 'Application';

    /**
     * Nome do cabeçalho do Usuário
     */
    const USER_HEADER_NAME = 'Authorization';

    /**
     * Nome do cabeçalho de Aplicação na sessão
     */
    const SESSION_APP_HEADER_NAME = 'GRAPHQL_APPLICATION';

    /**
     * Nome do cabeçalho de Usuário na sessão
     */
    const SESSION_USER_HEADER_NAME = 'GRAPHQL_AUTHORIZATION';
}
