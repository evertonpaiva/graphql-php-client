<?php

namespace GraphqlClient\Jwt;

use Firebase\JWT\JWT;
use GraphqlClient\Exception\ExpiredTokenException;
use DateTime;

/**
 * Class JwtDecoder
 *
 * Classe para gerenciar os tokens de autenticação do tipo JWT
 *
 * @package GraphqlClient\Jwt
 */
class JwtDecoder
{

    /**
     * Chave pública de autorização - ambiente de testes
     */
    const AUTORIZACAO_PUB_KEY_TESTE = <<<EOD
-----BEGIN PUBLIC KEY-----
MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEA0YFS941qmAr4XGnWoD2D
GXwallAf60jfsQlNjVZE7QMr4wuG+rUAeSqo86/69Yji5iBwgd7oEsJLi/32ljkZ
R4uxZbxZ/3vOBPQdSkvhhErblUMYb3TUckiprCFChtrEYcZbdTgSTQwxqPhFpn3j
HVaM8S6G9WRvzzwPvbO4CBGG4MRXXX1ZbUG5wS7C3fk0PRvO4yvrfR9dqWYS14+G
cIdzN/7n06IsVRrbMjMLgcOh25LkWTzhNnD8Th7G3hAKk5kXP8H2Y8GiUq9v9Qxs
uYi8yznBvuvWzICyu2ajB/G8LOhLCnLF6OoXSHXDLGsF4YTzn1nyrFxjHh0ubwQt
6qlMfUf0uczAibI7bPwhARfKzWLj+dVqw84H9P9bq+zeUPgDvUOULEw7v7i++yGj
8ZHbRLh9jLgvDgXdUwtIewPHlrfQaotLMb2I62vvGokcYBpomjodcXoR4ve+6X8J
WaprcEzFJDn9UqnNe4UuIHAm/TBYF+pHrCE3I5GWsF5+gfh0reXxe6GJawYfDyRn
hdbI6Z8y2qKjSX3nGvfTWXLjbJgHoFfrX1RVQbPh+Drgf9bc4roNvoxtCFLrtkwf
f+g6YHMVz+9IyVuCiK4+CVYHViOwRwb7UES/uWqmPliJ82UVgUNakqm3hKfvbVVt
PGWZ/N5yqVOx44LEGy4PzlECAwEAAQ==
-----END PUBLIC KEY-----
EOD;

    /**
     * Chave pública de autenticação - ambiente de testes
     */
    const AUTENTICACAO_PUB_KEY_TESTE = <<<EOD
-----BEGIN PUBLIC KEY-----
MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAwEvARpiodrH2pRdrem97
ckOveXE6STdoiU3oSx/XLcndjciR0hI0EgW6TjF0Hw50iWs8Y70Y/mIhsFgPsp0h
PFyTj7oNQ11w/4fwJN1VA+6cEL1ZjaEI1KVfK9VgIFeNZnRAncy25COLPFi8ulFu
WMsJKr6PooSWMVEyBRmDOMT/gibukdyhbSqMrOKbaIW+jB8exE/wik37Q/3h0VS3
DSbTG+ZoHLYfZA4MCH8fW9tUXiPMjeuhJa5VgGyR1R+qB1c6Wm0rIzPkadpCvabC
ienU+WOvkfUPwyxaNB3k9QzCkKpfRBrngUZoq7+LB5REaguJ0ACTiTerisvy7kz5
is9Kb2DfTQ7qujzUYiX0vsl0AhOgF21E+/utebuClBm8C2WjSRA0ioFHY0cqFmX1
Wx3d/DH/4un4ZmyY8ml6HFQArwMMzuvrnytWv8qIfi36wAA6pPUG+RzpAu/hYkh9
qhfPtBlc41oQRidxebljlfxgGSNB/P/UgQzAE5F0P6EwKa9NBrU2azt2+aiLWb7Q
qF0xb6o4HdjrwCQftbmYlZyAnf9LPdGdKAao4gg1lT3j6q03iigBI04ingMfxWWM
IPCWGv47FhV9HEl3Cn1df50NTvLuIJ3JF/x1KSksCoJKWputqfkzrKyWjg9xyj4w
69gcscgmys/+U15E1RRoJxECAwEAAQ==
-----END PUBLIC KEY-----
EOD;

    /**
     * Vetor de chaves públicas de autorização e autenticação por ambiente
     * @var array
     */
    private $pubKeyArray;

    /**
     * Nome do ambiente
     * @var
     */
    private $env;

    /**
     * Tipo de chave: Aplicação ou Autenticação
     * @var
     */
    private $type;

    /**
     * Token JWT
     * @var
     */
    private $jwt;

    public function __construct($jwt, $env, $type)
    {
        $this->pubKeyArray = array();

        $this->pubKeyArray['teste']['Application'] = self::AUTORIZACAO_PUB_KEY_TESTE;
        $this->pubKeyArray['teste']['Authorization'] = self::AUTENTICACAO_PUB_KEY_TESTE;

        $this->env = $env;
        $this->type = $type;
        $this->jwt = $jwt;
    }

    /**
     * Decodifica o campo útil do token
     * @return object|null
     */
    public function decode()
    {
        $pubKey = $this->pubKeyArray[$this->env][$this->type];

        $decoded = JWT::decode($this->jwt, $pubKey, array('RS256'));

        $decoded->expiraEm = new DateTime("@$decoded->exp");
        $this->checkTokenExpirado($decoded);
        $decoded->proximoExpirar = $this->proximoExpirar($decoded);

        return $decoded;
    }

    /**
     * Calcula se já está próximo da expiração da validade do token
     * Pega o tempo de expiração, e subtrai X minutos antes, pra avisar que o token está próximo de expirar
     * @param $decoded
     * @return bool
     * @throws \Exception
     */
    private function proximoExpirar($decoded)
    {
        // Calculando quantos segundos antes de expirar é pra indicar necessidade de renovação
        // Pedindo para renovar o token 15 min (15 min x 60 segundos) antes de expirar
        $epoch = $decoded->exp - (15 * 60);
        $exp = new DateTime("@$epoch");
        $now = new DateTime(now());

        return ($now > $exp);
    }

    /**
     * Testa se o token já está expirado
     * @param $decoded
     * @throws \Exception
     */
    private function checkTokenExpirado($decoded)
    {
        $exp = new DateTime("@$decoded->exp");
        $now = new DateTime(now());

        if ($now > $exp) {
            throw new ExpiredTokenException($this->type);
        }
    }
}
