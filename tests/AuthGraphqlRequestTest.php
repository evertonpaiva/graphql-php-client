<?php
namespace GraphqlClient\Tests;

use GraphqlClient\GraphqlRequest\AuthGraphqlRequest;
use GraphqlClient\GraphqlRequest\GraphqlRequest;
use GraphqlClient\Session\Session;
use stdClass;

class AuthGraphqlRequestTest extends GraphqlRequestTest
{
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
