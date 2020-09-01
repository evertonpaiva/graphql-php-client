<?php

namespace GraphqlClient\GraphqlRequest;

use GraphQL\Query;
use GraphQL\RawObject;

/**
 * Class AuthGraphqlRequest
 * Requisições GraphQL de Autenticação
 *
 * @package GraphqlClient\GraphqlRequest
 */
class AuthGraphqlRequest extends GraphqlRequest
{

    /**
     * Realiza o login da aplicação e do usuário
     *
     * @param object $request Requisição de autenticação
     * @return array Retorna um objeto cabeçalho com dois tokens (do tipo JWT bearer token) de aplicação e usuário
     */
    public function loginContaInstitucional($request)
    {
        // Limpando a sessão prévia, caso exista
        $this->cleanHeaders();

        $appInput = $this->generateAppInput();
        $userInput = $this->generateUserInput($request->containstitucional, $request->password);

        $input = new RawObject("{ appInput: $appInput userInput: $userInput }");

        $gql = (new Query('generateTokens'))
            ->setArguments(['input' => $input])
            ->setSelectionSet(
                [
                    (new Query('headers'))
                        ->setSelectionSet(
                            [
                                'Application',
                                'Authorization'
                            ]
                        )
                ]
            );

        $results = $this->getClient(AuthType::NO_AUTH)
            ->runQuery($gql);

        // Autenticacao passou, retornou cabecalhos de app e user
        $headers = $results->getResults()->data->generateTokens->headers;
        $this->storeHeaders($headers);
        return $headers;
    }

    /**
     * Retorna as informações do usuário logado
     * @return array Vetor com informações do usuário logado
     */
    public function usuarioLogadoInfo()
    {
        $gql = (
        new Query('me'))
            ->setSelectionSet(
                [
                    (
                    new Query('vinculos'))
                        ->setSelectionSet(
                            [
                                'tipoVinculo',
                                'listaVinculos'
                            ]
                        ),
                    'nome',
                    'cpf',
                    'email',
                    'containstitucional',
                    'idpessoa',
                    'endereco',
                    'bairro',
                    'municipio',
                    'cep'
                ]
            );

        $results = $this->getClient(AuthType::APP_USER_AUTH)
            ->runQuery($gql);
        return $results->getResults()->data->me;
    }
}
