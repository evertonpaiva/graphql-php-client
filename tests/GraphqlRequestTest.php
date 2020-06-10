<?php
namespace GraphqlClient\Tests;

use GraphqlClient\GraphqlRequest\GraphqlRequest;
use GraphqlClient\Session\Session;
use PHPUnit\Framework\TestCase;
use GraphqlClient\GraphqlRequest\AuthGraphqlRequest;
use stdClass;

class GraphqlRequestTest extends TestCase
{
    private $containstitucional;
    private $senha;

    protected function setUp(): void
    {
        $this->containstitucional = getenv('LDAP_USER_USERNAME');
        $this->senha = getenv('LDAP_USER_PASSWORD');
    }

    public function testLoginContaInstitucional() {
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

    public function testUserInfo()
    {
        // Carrega a classe de autenticação
        $authGraphqlRequest = new AuthGraphqlRequest();

        // Recupera as informações do usuário logado
        $userInfo = $authGraphqlRequest->usuarioLogadoInfo();

        $this->assertEquals($userInfo->nome, 'ADMINISTRADOR DO SISTEMA');
    }
}
