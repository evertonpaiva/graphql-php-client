<?php
namespace GraphqlClient\Tests;

use PHPUnit\Framework\TestCase;

abstract class GraphqlRequestTest extends TestCase
{
    protected $containstitucional;
    protected $senha;

    /**
     * Recupera variaveis de ambiente antes de executar os testes
     */
    protected function setUp(): void
    {
        $this->containstitucional = getenv('LDAP_USER_USERNAME');
        $this->senha = getenv('LDAP_USER_PASSWORD');
    }
}
