<?php

namespace GraphqlClient\GraphqlRequest;

use GraphQL\Client;
use GraphQL\Query;
use GraphQL\RawObject;
use GraphQL\Variable;
use GraphqlClient\Exception\DecodeTokenException;
use GraphqlClient\Exception\HeaderNotDefinedException;
use GraphqlClient\Exception\WrongInstanceRelationException;
use GraphqlClient\Exception\WrongInstancePaginationException;
use GraphqlClient\GraphqlQuery\BackwardPaginationQuery;
use GraphqlClient\GraphqlQuery\ForwardPaginationQuery;
use GraphqlClient\GraphqlQuery\GeneratorQuery;
use GraphqlClient\GraphqlQuery\PaginationQuery;
use GraphqlClient\GraphqlQuery\RelationQuery;
use GraphqlClient\GraphqlQuery\RelationType;
use GraphqlClient\Jwt\JwtDecoder;
use GraphqlClient\Session\Session;
use stdClass;

/**
 * Class GraphqlRequest
 * Controle de requisições HTTP de consultas GraphQL
 *
 * @package GraphqlClient\GraphqlRequest
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
     * Vetor com os campos disponíveis na entidade
     * @var array
     */
    private $fields;

    private $relations;

    /**
     * Tipo de autenticação necessária na API
     * @var string
     */
    private $authType;

    /**
     * Nome da query GraphQL
     * @var STRING
     */
    protected $queryName;

    /**
     * Vetor de argumentos da operação
     * @var array
     */
    protected $arguments;

    /**
     * Vetor com os nomes das variáveis
     * @var array
     */
    protected $variablesNames;

    /**
     * Vetor com os valores das variáveis
     * @var array
     */
    protected $variablesValues;

    /** Query GraphQL
     * @var Query
     */
    protected $gql;

    /**
     * Informações de paginação da query
     * @var PaginationQuery
     */
    protected $pagination;

    /**
     * GraphqlRequest constructor.
     * @param null $headers Objeto com token de aplicação e token de usuário
     */
    public function __construct(array $fields = null, $authType = null)
    {
        if (!is_null($fields)) {
            $this->setFields($fields);
        }
        if (!is_null($authType)) {
            $this->setAuthType($authType);
        }

        $this->arguments = [];
        $this->variablesNames = [];
        $this->variablesValues = [];
        $this->relations = [];

        $this->graphqlUrlArray['teste'] = ConfigRequest::GRAPHQL_URL_TESTE;
        $this->graphqlUrlArray['prod'] =  ConfigRequest::GRAPHQL_URL_PROD;

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
        if (is_null($this->headers) || !property_exists($this->headers, ConfigRequest::APP_HEADER_NAME)) {
            throw new HeaderNotDefinedException(ConfigRequest::APP_HEADER_NAME);
        }
    }

    /**
     * Checa se o cabeçalho de Usuário foi fornecido
     * @throws Exception
     */
    protected function checkUserHeader()
    {
        if (is_null($this->headers) || !property_exists($this->headers, ConfigRequest::USER_HEADER_NAME)) {
            throw new HeaderNotDefinedException(ConfigRequest::USER_HEADER_NAME);
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
     * @param bool $canRenew
     */
    private function checkToken($header, $type, $canRenew)
    {
        $token = explode(' ', $header)[1];
        try {
            $jwt = new JwtDecoder($token, $this->graphqlEnv, $type);
            $decoded = $jwt->decode();
            // Token está proximo de expirar
            // Renovando o token
            if ($decoded->proximoExpirar && $canRenew) {
                if ($type === ConfigRequest::APP_HEADER_NAME) {
                    $this->renewApp();
                } else {
                    $this->renewUser();
                }
            }
            return $decoded;
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            throw new DecodeTokenException($type, $errorMessage);
        }
    }

    /**
     * Carrega as variáveis de ambiente
     */
    private function loadEnvVars()
    {
        $this->appId = $this->getEnvValue(ConfigRequest::APP_ID_ENV);
        $this->appKey = $this->getEnvValue(ConfigRequest::APP_KEY_ENV);

        $this->graphqlEnv = $this->getEnvValue(ConfigRequest::GRAPHQL_ENVNAME_ENV);
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
     * Armazena as informações de cabeçalho na Sessão PHP
     * @param $headerSessionName Nome da variável a ser salva na sessão
     * @param $headerValue Valor da variável
     * @param $headerName Tipo de cabeçalho
     * @throws DecodeTokenException
     */
    private function storeHeader($headerSessionName, $headerValue, $headerName)
    {
        $this->startSession();

        $this->checkToken($headerValue, $headerName, false);
        Session::put($headerSessionName, $headerValue);
    }

    /**
     * Guarda os cabeçalhos na sessão
     * @param $headers
     */
    protected function storeHeaders($headers)
    {
        $this->storeHeader(
            ConfigRequest::SESSION_APP_HEADER_NAME,
            $headers->{ConfigRequest::APP_HEADER_NAME},
            ConfigRequest::APP_HEADER_NAME
        );
        $this->storeHeader(
            ConfigRequest::SESSION_USER_HEADER_NAME,
            $headers->{ ConfigRequest::USER_HEADER_NAME},
            ConfigRequest::USER_HEADER_NAME
        );
    }

    /**
     * Carrega os cabeçalhos da sessão
     */
    public function loadHeaders($renewRequest)
    {
        $this->startSession();

        $this->headers = new stdClass();

        // Caso seja uma requisição de renovação de token, não deixa a validação chamar a
        // renovação de token novamente
        $canRenew = !$renewRequest;

        if (!is_null(Session::get(ConfigRequest::SESSION_APP_HEADER_NAME))) {
            $appHeader = Session::get(ConfigRequest::SESSION_APP_HEADER_NAME);

            $decoded = $this->checkToken($appHeader, ConfigRequest::APP_HEADER_NAME, $canRenew);

            $this->headers->{ ConfigRequest::APP_HEADER_NAME} = new stdClass();
            $this->headers->{ ConfigRequest::APP_HEADER_NAME}->bearer = $appHeader;
            $this->headers->{ ConfigRequest::APP_HEADER_NAME}->payload = $decoded;
        }

        if (!is_null(Session::get(ConfigRequest::SESSION_USER_HEADER_NAME))) {
            $userHeader = Session::get(ConfigRequest::SESSION_USER_HEADER_NAME);

            $decoded = $this->checkToken($userHeader, ConfigRequest::USER_HEADER_NAME, $canRenew);

            $this->headers->{ ConfigRequest::USER_HEADER_NAME} = new stdClass();
            $this->headers->{ ConfigRequest::USER_HEADER_NAME}->bearer = $userHeader;
            $this->headers->{ ConfigRequest::USER_HEADER_NAME}->payload = $decoded;
        }
    }

    /**
     * Limpa os cabeçalhos da sessão
     */
    protected function cleanHeaders()
    {
        $this->startSession();

        if (!is_null(Session::get(ConfigRequest::SESSION_APP_HEADER_NAME))) {
            Session::forget(ConfigRequest::SESSION_APP_HEADER_NAME);
        }

        if (!is_null(Session::get(ConfigRequest::SESSION_USER_HEADER_NAME))) {
            Session::forget(ConfigRequest::SESSION_USER_HEADER_NAME);
        }
    }

    /**
     * Carrega o Client que se conecta ao servidor GraphQL
     * O tipo de autenticação define quais os cabeçalhos serão enviados a cada requisição
     *
     * @return Client
     */
    private function loadClient()
    {
        // Caso os cabeçalhos tenham sido enviados, contruindo o cliente GraphQL com as informações de cabeçalho
        // Cabeçalhos foram enviados e
        // Enviou cabeçalho da aplicação
        if (is_object($this->headers) && property_exists($this->headers, ConfigRequest::APP_HEADER_NAME)) {
            $headersConstructor = [];
            $headersConstructor[ ConfigRequest::APP_HEADER_NAME] =
                $this->headers->{ ConfigRequest::APP_HEADER_NAME}->bearer;
            // Enviou cabeçalho do usuário
            if (property_exists($this->headers, ConfigRequest::USER_HEADER_NAME)) {
                $headersConstructor[ ConfigRequest::USER_HEADER_NAME] =
                    $this->headers->{ ConfigRequest::USER_HEADER_NAME}->bearer;
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

    /**
     * Carrega o client GraphQL baseado no tipo de autenticação necessária
     * @param null $authType
     * @return Client
     * @throws Exception
     */
    protected function getClient($authType = null)
    {
        if (is_null($authType)) {
            $authType = $this->getAuthType();
        }

        // Carregando os cabeçalhos
        $this->loadHeaders(false);

        // Verificando se os cabeçalhos dependendo do tipo de autenticação
        if ($authType === AuthType::APP_USER_AUTH) {
            $this->checkHeaders();
        } elseif ($authType === AuthType::APP_AUTH) {
            $this->checkAppHeader();
        }

        $client = $this->loadClient();

        return $client;
    }

    /**
     * Renova o token de Usuário
     * @return mixed
     * @throws DecodeTokenException
     */
    public function renewUser()
    {
        $gql = <<<QUERY
query {
  renewUser
}
QUERY;

        // Carregando os cabeçalhos
        $this->loadHeaders(true);

        // Carregando o client diretamente, sem checagem para evitar loop
        $client = $this->loadClient();

        $results = $client->runRawQuery($gql);

        $token = $results->getResults()->data->renewUser;
        $header = 'Bearer '.$token;

        //Armazenar o novo token gerado
        $this->storeHeader(ConfigRequest::SESSION_USER_HEADER_NAME, $header, ConfigRequest::USER_HEADER_NAME);

        return $results->getResults()->data->renewUser;
    }

    /**
     * Renova o token de Aplicação
     * @return mixed
     * @throws DecodeTokenException
     */
    public function renewApp()
    {
        $gql = <<<QUERY
query {
  renewApp
}
QUERY;

        // Carregando os cabeçalhos
        $this->loadHeaders(true);

        // Carregando o client diretamente, sem checagem para evitar loop
        $client = $this->loadClient();

        $results = $client->runRawQuery($gql);

        $token = $results->getResults()->data->renewApp;
        $header = 'Bearer '.$token;

        //Armazenar o novo token gerado
        $this->storeHeader(ConfigRequest::SESSION_APP_HEADER_NAME, $header, ConfigRequest::APP_HEADER_NAME);
        return $results->getResults()->data->renewApp;
    }

    /**
     * Armazena os campos da query
     * @param array $fields
     */
    public function setFields(array $fields)
    {
        $this->fields = $fields;
    }

    /**
     * Recupera os campos da query
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    protected function addRelation(RelationQuery $relation)
    {
        $className = $relation->getRelationClass();
        $relationInstance = $relation->getRelation();

        // Caso não tenha enviado o objeto relation instanciado, cria uma instância padrão
        if (is_null($relationInstance)) {
            $graphqlRequest = new $className();
            $relation->setRelation($graphqlRequest);
        } else {
            if (!$relationInstance instanceof $className) {
                throw new WrongInstanceRelationException($relation->getRelationName(), $className);
            }
        }

        $relationType = $relation->getType();
        $paginationInstance = $relation->getPagination();

        // Se for uma relation do tipo paginado
        // Caso não tenha enviado o objeto relation instanciado, cria uma instância padrão
        if ($relationType === RelationType::PAGINATED) {
            if (is_null($paginationInstance)) {
                $pagination = new ForwardPaginationQuery();
                $relation->setPagination($pagination);
            } else {
                if (!$paginationInstance instanceof ForwardPaginationQuery ||
                    !$paginationInstance instanceof BackwardPaginationQuery
                ) {
                    throw new WrongInstancePaginationException($className, $relation->getRelationName());
                }
            }
        }

        $this->relations[] = $relation;
    }

    /**
     * Armazena o tipo de autenticação
     * @param $authType
     */
    public function setAuthType($authType)
    {
        $this->authType = $authType;
    }

    /**
     * Recupera o tipo de autenticação
     * @return string
     */
    public function getAuthType()
    {
        return $this->authType;
    }

    /**
     * Gera uma query GraphQL para registros simples
     */
    protected function generateSingleQuery(): void
    {
        $generated = GeneratorQuery::generateSingleQuery(
            $this->queryName,
            $this->variablesNames,
            $this->arguments,
            $this->fields,
            $this->relations
        );

        $this->gql = $generated->gql;

        foreach ($generated->variablesValues as $k => $vv) {
            $this->variablesValues[$k] = $vv;
        }
    }

    /**
     * Gera uma query GraphQL para informações do tipo paginadas obedecendo o padrão Relay
     * @return $this
     */
    public function generatePaginatedQuery($sufix = '')
    {
        $paginationInstance = $this->pagination;

        // Se for uma relation do tipo paginado
        // Caso não tenha enviado o objeto relation instanciado, cria uma instância padrão
        if (is_null($paginationInstance)) {
            $this->pagination = new ForwardPaginationQuery();
        } else {
            if (!($paginationInstance instanceof ForwardPaginationQuery ||
                    $paginationInstance instanceof BackwardPaginationQuery)
            ) {
                throw new WrongInstancePaginationException($this->queryName);
            }
        }

        $size = $this->pagination->getSize();
        $sizeName = $this->pagination->getSizeName();
        $cursor = $this->pagination->getCursor();
        $cursorName = $this->pagination->getCursorName();

        $this->variablesNames[] = new Variable($sizeName.$sufix, 'PaginationLimit', true);
        $this->variablesValues[$sizeName.$sufix] = $size;

        if (!is_null($cursor)) {
            $this->variablesNames[] = new Variable($cursorName.$sufix, 'String', true);
            $this->variablesValues[$cursorName.$sufix] = $cursor;

            // Cria a variável de paginação
            //Para frente: '{first: $first, after: $after}'
            // Para trás:  '{last: $last, before: $before}'
            $paginationString = '{'.$sizeName.': $'.$sizeName.$sufix.', '.$cursorName.': $'.$cursorName.$sufix.'}';
            $this->arguments['pagination'] = new RawObject($paginationString);
        } else {
            // Cria a variável de paginação
            // Para frente: '{first: $first}'
            // Para trás:  '{last: $last}'
            $paginationString = '{'.$sizeName.': $'.$sizeName.$sufix.'}';
            $this->arguments['pagination'] = new RawObject($paginationString);
        }

        foreach ($this->relations as $r) {
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
                    $this->variablesNames[] = $v;
                }

                foreach ($relation->getVariablesValues() as $k => $vv) {
                    $this->variablesValues[$k] = $vv;
                }
            }
            $this->fields[] = $fieldsRelation;
        }

        $this->gql = new Query($this->queryName);

        $this->gql->setVariables($this->variablesNames);

        $this->gql->setArguments($this->arguments);

        $this->gql = GeneratorQuery::generatePageInfoField($this->gql, $this->getFields());

        return $this;
    }

    /**
     * Executa query GraphQL e retorna os dados recebidos como resposta
     * @return mixed
     */
    public function getResults()
    {
        $results = $this->getClient()
            ->runQuery($this->gql, false, $this->variablesValues);

        return $results->getResults()->data->{$this->queryName};
    }

    /**
     * Limpa as informações de query GraphQL, deixando o objeto pronto para criar uma nova query
     */
    protected function clearQueryObjects()
    {
        $this->arguments = [];
        $this->variablesNames = [];
        $this->variablesValues = [];
        $this->gql = null;
    }

    public function setQueryName(string $queryName)
    {
        $this->queryName = $queryName;
    }

    public function setPagination(PaginationQuery $pagination)
    {
        $this->pagination = $pagination;
    }

    public function getGql(): Query
    {
        return $this->gql;
    }

    public function getVariablesNames()
    {
        return $this->variablesNames;
    }

    public function getArguments()
    {
        return $this->arguments;
    }

    public function getVariablesValues()
    {
        return $this->variablesValues;
    }
}
