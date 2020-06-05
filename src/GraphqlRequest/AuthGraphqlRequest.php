<?php

namespace GraphqlClient\GraphqlRequest;

use GraphQL\Query;
use GraphQL\RawObject;

/**
 * Requisições GraphQL de Autenticação
 *
 * Class AuthGraphqlRequest
 * @package App\Http\GraphqlRequests
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

        $results = $this->client->runQuery($gql);
        return $results->getResults()->data->generateTokens->headers;
    }

    /**
     * Retorna as informações do usuário logado
     * @return array Vetor com informações do usuário logado
     */
    public function usuarioLogadoInfo()
    {
        $this->checkHeaders();

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
                    'cpf'
                ]
            );

        $results = $this->client->runQuery($gql);
        return $results->getResults()->data->me;
    }
}
