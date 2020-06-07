<?php

namespace GraphqlClient\Session;

/**
 * Class Session
 *
 * Classe para acessar as variáveis de sessão do PHP
 *
 * @package GraphqlClient\Session
 */
class Session
{

    /**
     * Coloca uma variável na sessão
     * @param $key
     * @param $value
     */
    public static function put($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Recupera uma variável na sessão
     * @param $key
     * @return mixed|null
     */
    public static function get($key)
    {
        return (isset($_SESSION[$key]) ? $_SESSION[$key] : null);
    }

    /**
     * Remover uma variável da sessão
     * @param $key
     */
    public static function forget($key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * Inicia a sessão, quando ela ainda não foi iniciada
     */
    public static function startSession()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
}
