<?php

namespace GraphqlClient\GraphqlRequest;

use GraphQL\Client;
use GraphQL\RawObject;
use Error;

/**
 * Controle de requisições HTTP de consultas GraphQL
 *
 * Class GraphqlRequest
 * @package App\Http\GraphqlRequests
 */
class GraphqlRequest
{
    /**
     * @var string Id da aplicação no controle de microsserviços
     */
    protected $appId;

    /**
     * @var string Key da aplicação no controle de microsserviços
     */
    protected $appKey;

    /**
     * @var Client Cliente para conectar no servidor Graphql
     */
    protected $client;

    /**
     * GraphqlRequest constructor.
     * @param null $headers Objeto com token de aplicação e token de usuário
     */
    public function __construct($headers = null)
    {
        $this->appId = $this->getEnvValue('GRAPHQL_APP_ID');
        $this->appKey = $this->getEnvValue('GRAPHQL_APP_KEY');

        $graphlUrl = $this->getEnvValue('GRAPHQL_URL');

        // Caso os cabeçalhos tenham sido enviados, contruindo o cliente GraphQL com as informações de cabeçalho
        if (is_object($headers)
            && property_exists($headers, 'Application')
            && property_exists($headers, 'Authorization')
        ) {
            $this->client = new Client(
                $graphlUrl,
                [
                    'Application' => $headers->Application,
                    'Authorization' => $headers->Authorization
                ]
            );
        } else {
            $this->client = new Client(
                $graphlUrl
            );
        }
    }

    /**
     * Gera o input oara os dados de aplicação
     *
     * @return RawObject Entrada na controle de aplicações
     */
    protected function generateAppInput()
    {
        return new RawObject("{ appId: \"{$this->appId}\" appKey: \"{$this->appKey}\" }");
    }

    /**
     * Gera o input para os dados de usuário
     *
     * @param String $login Login do usuário
     * @param String $password Senha do usuário
     * @return RawObject Entrada para controle de usuários
     */
    protected function generateUserInput(String $login, String $password)
    {
        return new RawObject("{ login: \"${login}\" password: \"${password}\" }");
    }

    /**
     * Busca o valor da variável definida como variável de ambiente
     * @param $envName Nome da variável de ambiente
     * @return array|false|string
     */
    private function getEnvValue($envName)
    {
        if (!getenv($envName)) {
            throw new Error('Variável de ambiente '.$envName.' não definida');
        }
        return getenv($envName);
    }
}