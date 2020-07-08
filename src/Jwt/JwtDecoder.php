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

    const AUTORIZACAO_PUB_KEY_LOCAL = <<<EOD
-----BEGIN PUBLIC KEY-----
MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAyBXXr3slY7l6HzB0Q1Z1
mhPMm4LYXe8UL1OSoQKTDepeojcKWCeHo1zhlZySJ4NEFM32mYMMwhoV95YlQJQU
gsBiq/NOlCEx8oCGDMKjrBOqlab/S/tprQuGZ5VwVOVvTacCRbaa/HpQNaOQhRS6
E2X/jizRZpSjt8vk7i2VNqaexWNbKk99vEGrY9iqmXijZtRGf0awjjAsGrpVhOwy
+qvf5pWkA/DfrkL1a+Jp9dKdYEJvnAhy0FX8KJsjPbOkuUxey1OT0fI65229RxKw
gr5yxIJZ/swfFufzQ2HXaKJIMwTHWqdR91AYyqo6KFg/uwkw12544d99hv8gv4QJ
pDbRIfwipl1dLbdKVFzlr98Iyc/hEnCqtio9g8zAyb4dAEPJA2/AweI+Zfo1Au8S
bSq5GE7ONiGizrxM8GnkT4HCbGkTD+74nz5v53/z25w3d5V/VjEfkpLWRjHyB8rl
7VxOdcJiI/uNkBJPUxYm2FMJqknVegVrNwFgVXKy7iKT0WrRBdzxFksMlhJVyek8
N/waP0dP7EYCkZuVkpEEecMCGq7vw1kiCw9riq42s1IOsS75J9ObTmv65fPp1rUF
BVkxiR2JhZ3ElsSsEBvxIEImesmPVp5JttaSQubUZ/LYp/4UCEGbUwXCfbJ9xw5v
UdlC0eGkzD8f4HG1Obfzn+0CAwEAAQ==
-----END PUBLIC KEY-----
EOD;


    /**
     * Chave pública de autorização - ambiente de testes
     */
    const AUTORIZACAO_PUB_KEY_TESTE = <<<EOD
-----BEGIN PUBLIC KEY-----
MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEA1qaaiiN46Hnpdoo5ORr2
eryR9EZ7hA/GW4a5EIAcZHs8OK2lZgB8qsx+wFthNmF0jGCfrJCBL1sj1KFUUNVj
ppXbT/XPw0Ywu8pBIWBEz2GLuta3OiC+5z4CVQEdjbRYiAFvtUyWoziA6R3LxSxc
x2b0GkLZBfAlggXlyQE+LzANfYqggvyQzWMv0tptdCGNInORblwhF3WVf0WWilml
0V//pU3KIMzlwR1Yq1r4lzwGnAkk6O8Ma0HDH1JqLwm2zz8C2sN8OaoqvJcEBErX
my7OG9J4BvrHJJh81HyMff+5HMajpe56e7A4QyIVwcsC0kFrJ9LKJDmQDvZ/ywTY
ISFWsOzszL27mu5YWPPA49TVT5TYpsjh6zWi/qNYfnguRfQmN6DI9cKOyhsxeE7v
/xZjSlvCZW/iGD2PeaJvM7/qO3lKzPUd68asUXLAqmXtRTyMvY3cdXMtpfvhUcmX
DG6OULCds8fSHYSZyUf5KFAyEH9gxlc+wmUV5Hbq8wBzBa7e/L1DBEukcElB3Dcz
nNY6R7u/Yj3Aqc7kbbSahGbxMnyNuzysHO5lAak+Flb3ZCulL34fXPQfZzIKdpn/
Wcu91s4GF+l46xDzLSoSiARCbRSECUowmrAzQR77igIewqRicCP3swDpcKDraspz
d6j5zU+rjGXdsuYb0kSKg90CAwEAAQ==
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

        $this->pubKeyArray['local']['Application'] = self::AUTORIZACAO_PUB_KEY_LOCAL;
        $this->pubKeyArray['local']['Authorization'] = self::AUTENTICACAO_PUB_KEY_TESTE;

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

        // Permitir 10 segundos de tolerância entre a hora do servidor que gera o token
        // este servidor web que valida o token.
        JWT::$leeway = 10;
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
        $now = new DateTime(date("Y-m-d H:i:s"));

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
        $now = new DateTime(date("Y-m-d H:i:s"));

        if ($now > $exp) {
            throw new ExpiredTokenException($this->type);
        }
    }
}
