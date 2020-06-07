<?php

namespace GraphqlClient\GraphqlRequest;

use GraphQL\Client;
use GraphQL\RawObject;
use GraphqlClient\Jwt\JwtDecoder;
use GraphqlClient\Session\Session;
use Error;
use Exception;
use stdClass;

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
     * Variável de ambiente para a URL do servidor GraphQL do ambiente de testes
     */
    const GRAPHQL_URL_TESTE = 'http://micro-teste.dds.ufvjm.edu.br/';

    /**
     * Variável de ambiente para a URL do servidor GraphQL do ambiente de produção
     */
    const GRAPHQL_URL_PROD = 'http://micro.dds.ufvjm.edu.br/';

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
     * URL do servidor GraphQL
     * @var
     */
    private $graphqlUrl;

    /**
     * Ambiente do servidor GraphQL
     * @var
     */
    private $graphqlEnv;

    /**
     * Armazena os tokens de autenticação de Aplicação e Usuário
     * @var stdClass|null Objeto contento os tokens
     */
    protected $headers;

    /**
     * Vetor de URLs de servidores GraphQL por ambiente
     * @var
     */
    private $graphqlUrlArray;

    /**
     * GraphqlRequest constructor.
     * @param null $headers Objeto com token de aplicação e token de usuário
     */
    public function __construct()
    {
        $this->graphqlUrlArray['teste'] = self::GRAPHQL_URL_TESTE;
        $this->graphqlUrlArray['prod'] = self::GRAPHQL_URL_PROD;

        // Carrega as variáveis de ambiente
        $this->loadEnvVars();
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
        if (is_null($this->headers) || !property_exists($this->headers, self::APP_HEADER_NAME)) {
            throw \Exception('Cabeçalho de Aplicação não definido. '.self::MSG_EMPTY_HEADER);
        }
    }

    /**
     * Checa se o cabeçalho de Usuário foi fornecido
     * @throws Exception
     */
    protected function checkUserHeader()
    {
        if (is_null($this->headers) || !property_exists($this->headers, self::USER_HEADER_NAME)) {
            throw \Exception('Cabeçalho de Usuário não definido. '.self::MSG_EMPTY_HEADER);
        }
    }

    /**
     * Checa se os cabeçalhos de Usuário e Aplicação foram fornecidos
     * @throws Exception
     */
    private function checkHeaders()
    {
        $this->checkAppHeader();
        $this->checkUserHeader();
    }

    /**
     * Checha se um token é válido
     * @param $header
     * @param $type
     */
    private function checkToken($header, $type)
    {
        $token = explode(' ', $header)[1];
        $jwt = new JwtDecoder($token, $this->graphqlEnv, $type);
        try {
            $jwt->decode();
            //dd($decoded);
            //TO-DO @calcular se o token está expirado
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            throw \Exception('Não foi possível decodificar o token '.$type.' Mensagem: '.$errorMessage);
        }
    }

    /**
     * Carrega as variáveis de ambiente
     */
    private function loadEnvVars()
    {
        $this->appId = $this->getEnvValue(self::APP_ID_ENV);
        $this->appKey = $this->getEnvValue(self::APP_KEY_ENV);

        $this->graphqlEnv = $this->getEnvValue(self::GRAPHQL_ENVNAME_ENV);
        $this->graphqlUrl = $this->graphqlUrlArray[$this->graphqlEnv];
    }

    /**
     * Inicia a sessão PHP
     */
    private function startSession()
    {
        Session::startSession();
    }

    /**
     * Guarda os cabeçalhos na sessão
     * @param $headers
     */
    protected function storeHeaders($headers)
    {
        $this->startSession();

        $this->checkToken($headers->{self::APP_HEADER_NAME}, self::APP_HEADER_NAME);
        Session::put(self::SESSION_APP_HEADER_NAME, $headers->{self::APP_HEADER_NAME});

        $this->checkToken($headers->{self::USER_HEADER_NAME}, self::USER_HEADER_NAME);
        Session::put(self::SESSION_USER_HEADER_NAME, $headers->{self::USER_HEADER_NAME});
    }

    /**
     * Carrega os cabeçalhos da sessão
     */
    private function loadHeaders()
    {
        $this->startSession();

        $this->headers = \stdClass();

        if (!is_null(Session::get(self::SESSION_APP_HEADER_NAME))) {
            $appHeader = Session::get(self::SESSION_APP_HEADER_NAME);
            $this->checkAppToken($appHeader, self::APP_HEADER_NAME);
            $this->headers->{self::APP_HEADER_NAME} = $appHeader;
        }

        if (!is_null(Session::get(self::SESSION_USER_HEADER_NAME))) {
            $userHeader = Session::get(self::SESSION_USER_HEADER_NAME);
            $this->checkUserToken($userHeader, self::USER_HEADER_NAME);
            $this->headers->{self::USER_HEADER_NAME} = $userHeader;
        }
    }

    /**
     * Limpa os cabeçalhos da sessão
     */
    protected function cleanHeaders()
    {
        $this->startSession();

        if (!is_null(Session::get(self::SESSION_APP_HEADER_NAME))) {
            Session::forget(self::SESSION_APP_HEADER_NAME);
        }

        if (!is_null(Session::get(self::SESSION_USER_HEADER_NAME))) {
            Session::forget(self::SESSION_USER_HEADER_NAME);
        }
    }

    /**
     * Carrega o client GraphQL baseado no tipo de autenticação necessária
     * @param null $authType
     * @return Client
     * @throws Exception
     */
    protected function getClient($authType = null)
    {
        // Carregando os cabeçalhos
        $this->loadHeaders();

        if (is_null($authType)) {
            throw \Exception('Defina o tipo de autenticação para sua requisição graphql.');
        }

        // Verificando se os cabeçalhos dependendo do tipo de autenticação
        if ($authType === AuthType::APP_USER_AUTH) {
            $this->checkHeaders();
        } elseif ($authType === AuthType::APP_AUTH) {
            $this->checkAppHeader();
        }

        // Caso os cabeçalhos tenham sido enviados, contruindo o cliente GraphQL com as informações de cabeçalho
        // Cabeçalhos foram enviados e
        // Enviou cabeçalho da aplicação
        if (is_object($this->headers) && property_exists($this->headers, self::APP_HEADER_NAME)) {
            $headersConstructor = [];
            $headersConstructor[self::APP_HEADER_NAME] = $this->headers->{self::APP_HEADER_NAME};
            // Enviou cabeçalho do usuário
            if (property_exists($this->headers, self::USER_HEADER_NAME)) {
                $headersConstructor[self::USER_HEADER_NAME] = $this->headers->{self::USER_HEADER_NAME};
            }

            // Criando a instância do client graphql e enviando os cabeçalhos fornecidos
            $client = new Client(
                $this->graphqlUrl,
                $headersConstructor
            );
        } else {
            $this->headers = null;
            $client = new Client(
                $this->graphqlUrl
            );
        }

        return $client;
    }
}
