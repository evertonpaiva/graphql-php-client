<?php

namespace GraphqlClient\GraphqlRequest;

use GraphQL\Client;
use GraphQL\RawObject;
use Error;
use Exception;

/**
 * Controle de requisições HTTP de consultas GraphQL
 *
 * Class GraphqlRequest
 * @package App\Http\GraphqlRequests
 */
class GraphqlRequest
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
     * Variável de ambiente para a URL do servidor GraphQL
     */
    const GRAPHQL_URL_ENV = 'GRAPHQL_URL';

    /**
     * Nome do cabeçalho da Aplicação
     */
    const APP_HEADER_NAME = 'Application';

    /**
     * Nome do cabeçalho do Usuário
     */
    const USER_HEADER_NAME = 'Authorization';

    /**
     * Mensagem de erro para cabeçalho não fornecido
     */
    const MSG_EMPTY_HEADER = 'Recupere o cabeçalho da sessão e passe-o ao construtor.';

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
     * Armazena os tokens de autenticação de Aplicação e Usuário
     * @var stdClass|null Objeto contento os tokens
     */
    protected $headers;

    /**
     * GraphqlRequest constructor.
     * @param null $headers Objeto com token de aplicação e token de usuário
     */
    public function __construct($headers = null)
    {
        $this->appId = $this->getEnvValue(self::APP_ID_ENV);
        $this->appKey = $this->getEnvValue(self::APP_KEY_ENV);

        $graphlUrl = $this->getEnvValue(self::GRAPHQL_URL_ENV);

        // Caso os cabeçalhos tenham sido enviados, contruindo o cliente GraphQL com as informações de cabeçalho
        // Cabeçalhos foram enviados e
        // Enviou cabeçalho da aplicação
        if (is_object($headers) && property_exists($headers, self::APP_HEADER_NAME)) {
            $this->headers = new stdClass();
            $this->headers->{self::APP_HEADER_NAME} = $headers->{self::APP_HEADER_NAME};

            $headersConstructor = [];
            $headersConstructor[self::APP_HEADER_NAME] = $headers->{self::APP_HEADER_NAME};
            // Enviou cabeçalho do usuário
            if (property_exists($headers, self::USER_HEADER_NAME)) {
                $this->headers->{self::USER_HEADER_NAME} = $headers->{self::USER_HEADER_NAME};
                $headersConstructor[self::USER_HEADER_NAME] = $headers->{self::USER_HEADER_NAME};
            }

            // Criando a instância do client graphql e enviando os cabeçalhos fornecidos
            $this->client = new Client(
                $graphlUrl,
                $headersConstructor
            );
        } else {
            $this->headers = null;
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

    /**
     * Checa se o cabeçalho de Aplicação foi fornecido
     * @throws Exception
     */
    protected function checkAppHeader()
    {
        if (is_null($this->headers) || is_null($this->headers->{self::APP_HEADER_NAME})) {
            throw new Exception('Cabeçalho de Aplicação não definido. '.self::MSG_EMPTY_HEADER);
        }
    }

    /**
     * Checa se o cabeçalho de Usuário foi fornecido
     * @throws Exception
     */
    protected function checkUserHeader()
    {
        if (is_null($this->headers) || !property_exists($this->headers, self::USER_HEADER_NAME)) {
            throw new Exception('Cabeçalho de Usuário não definido. '.self::MSG_EMPTY_HEADER);
        }
    }

    /**
     * Checa se os cabeçalhos de Usuário e Aplicação foram fornecidos
     * @throws Exception
     */
    protected function checkHeaders()
    {
        $this->checkAppHeader();
        $this->checkUserHeader();
    }
}
