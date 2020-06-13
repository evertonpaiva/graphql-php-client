<?php
namespace GraphqlClient\Tests;

use GraphqlClient\GraphqlRequest\AuthGraphqlRequest;
use GraphqlClient\GraphqlRequest\GraphqlRequest;
use GraphqlClient\Session\Session;
use stdClass;
use GraphqlClient\Exception\HeaderNotDefinedException;

class AuthGraphqlRequestTest extends GraphqlRequestTest
{
    /**
     * Tenta acessar informações do usuário logado quando a sessão
     * não possui os cabeçalhos de app e usuário, exceção esperada
     */
    public function testUserInfoSemEstarLogado()
    {
        // Tipo de exceção esperada
        $this->expectException(HeaderNotDefinedException::class);

        Session::startSession();
        Session::forget(GraphqlRequest::SESSION_APP_HEADER_NAME);
        Session::forget(GraphqlRequest::SESSION_USER_HEADER_NAME);

        // Carrega a classe de autenticação
        $authGraphqlRequest = new AuthGraphqlRequest();

        // Recupera as informações do usuário logado
        $userInfo = $authGraphqlRequest->usuarioLogadoInfo();
    }

    /**
     * Testa o login na API com a Conta Institucional.
     * Em caso de sucesso, os tokens ficarão salvos na sessão para os demais testes
     */
    public function testLoginContaInstitucional()
    {
        $request = new stdClass();
        $request->containstitucional = $this->containstitucional;
        $request->password = $this->senha;

        // Carrega a classe de autenticação
        $authGraphqlRequest = new AuthGraphqlRequest();

        // Tenta realizar o login na Conta Institucional
        $authGraphqlRequest->loginContaInstitucional($request);
        $this->assertNotNull(Session::get(GraphqlRequest::SESSION_APP_HEADER_NAME));
        $this->assertNotNull(Session::get(GraphqlRequest::SESSION_USER_HEADER_NAME));
    }

    /**
     * Recupera as informações do usuário logado
     */
    public function testUserInfo()
    {
        // Carrega a classe de autenticação
        $authGraphqlRequest = new AuthGraphqlRequest();

        // Recupera as informações do usuário logado
        $userInfo = $authGraphqlRequest->usuarioLogadoInfo();

        $this->assertEquals($userInfo->nome, 'ADMINISTRADOR DO SISTEMA');
    }
}
