<?php
namespace GraphqlClient\Tests;

use GraphqlClient\GraphqlRequest\AuthGraphqlRequest;
use GraphqlClient\GraphqlRequest\ConfigRequest;
use GraphqlClient\Session\Session;
use stdClass;
use GraphqlClient\Exception\HeaderNotDefinedException;
use GraphqlClient\Exception\DecodeTokenException;

class AuthGraphqlRequestTest extends GraphqlRequestTest
{
    /**
     * Tenta acessar informações do usuário logado quando a sessão
     * não possui os cabeçalhos de app e usuário, exceção esperada
     */
    public function testHeaderNotDefinedException()
    {
        // Tipo de exceção esperada
        $this->expectException(HeaderNotDefinedException::class);

        Session::startSession();
        Session::forget(ConfigRequest::SESSION_APP_HEADER_NAME);
        Session::forget(ConfigRequest::SESSION_USER_HEADER_NAME);

        // Carrega a classe de autenticação
        $authGraphqlRequest = new AuthGraphqlRequest();

        // Recupera as informações do usuário logado
        $authGraphqlRequest->usuarioLogadoInfo();
    }

    /**
     * Tenta acessar informações do usuário logado quando a sessão
     * possui cabeçalhos inválidos, exceção esperada
     */
    public function testDecodeTokenException()
    {
        // Tipo de exceção esperada
        $this->expectException(DecodeTokenException::class);

        Session::startSession();
        Session::put(ConfigRequest::SESSION_APP_HEADER_NAME, 'Bearer eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6ImFwcC10b2tlbi1rZXkifQ.eyJzdWIiOiJjNGRkZDQyOC01MzQwLTQ0MTUtYTZkYi04YzU5YTUyOWQ5MDMiLCJpZEFwbGljYXRpdm8iOjUsIm5vbWUiOiJTSUVYQyAtIEJvbHNhcyIsImFwcElkIjoiYzRkZGQ0MjgtNTM0MC00NDE1LWE2ZGItOGM1OWE1MjlkOTAzIiwiaWF0IjoxNjYxNTQxMDUwLCJleHAiOjE2NjE2Mjc0NTAsImlzcyI6Imh0dHA6Ly8xMC4yNTQuMTYuMTQzOjMwMDk5In0.fSUtUsE8KpJ8GQybBq5Rh8FqZ_DrZSvhtE9M_t9ufGkV95AeAPy_zQuP4jNE2TkfCxyjF7jMwTM1PwodKquha0vlLcYiG-bSJ9fC5ktKhCzTEIUndyNUDTr8gj4G0H88OLg2eK_XYHb0M75pflSzJDG20RsuGrV-0C8OK8e-BSIdYULDw233Bg2an8jD87f5FWzTA9DKp8AVicBQNZ6rehHWHuP3o64TnI7bC0xGWcREHM4avr3PMnaC7YOdw70xsYXTN2j9a8dE4ljZzoh9R0a7BUHeCduRLfjZ2v7YE7mDiCY88RMDyJszJIc2yzQJOlxLjJ-DYqtT2Vtl7dszeIRPW9FViCmp9iSp4njWcad4eglaIiIwA7yzrQNXm9XyvNnzY_7sCYUk7HEEAknpuxogHeoN6ebbI1bcF1ER0ClroUjcnOSVaeEX0y6VCdQNeT22p8wtDtS4uzA_BLGtsduNAoGOQenvOBAC7j_yZv-C8XxvQMJ3h5s44zSj09AFbzUXlMLKgg_gy9373WmxFclsRTZRCeOV2Jln5Q6vXJgIiXYyGOhiowPX9ttuoiD6mtD_qF7JLd2k4r1I78k7CgTHjPmasWFLpv5sjLd8-7-rLcjQAbpZOUmnu-Y_0z9e64cL5s27Xh8NEPlwkPL0vENDdPBVK1kt09OpZLiQ7Iy');
        Session::put(ConfigRequest::SESSION_USER_HEADER_NAME, 'Bearer eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6ImFwcC10b2tlbi1rZXkifQ.eyJzdWIiOiJjNGRkZDQyOC01MzQwLTQ0MTUtYTZkYi04YzU5YTUyOWQ5MDMiLCJpZEFwbGljYXRpdm8iOjUsIm5vbWUiOiJTSUVYQyAtIEJvbHNhcyIsImFwcElkIjoiYzRkZGQ0MjgtNTM0MC00NDE1LWE2ZGItOGM1OWE1MjlkOTAzIiwiaWF0IjoxNjYxNTQxMDUwLCJleHAiOjE2NjE2Mjc0NTAsImlzcyI6Imh0dHA6Ly8xMC4yNTQuMTYuMTQzOjMwMDk5In0.fSUtUsE8KpJ8GQybBq5Rh8FqZ_DrZSvhtE9M_t9ufGkV95AeAPy_zQuP4jNE2TkfCxyjF7jMwTM1PwodKquha0vlLcYiG-bSJ9fC5ktKhCzTEIUndyNUDTr8gj4G0H88OLg2eK_XYHb0M75pflSzJDG20RsuGrV-0C8OK8e-BSIdYULDw233Bg2an8jD87f5FWzTA9DKp8AVicBQNZ6rehHWHuP3o64TnI7bC0xGWcREHM4avr3PMnaC7YOdw70xsYXTN2j9a8dE4ljZzoh9R0a7BUHeCduRLfjZ2v7YE7mDiCY88RMDyJszJIc2yzQJOlxLjJ-DYqtT2Vtl7dszeIRPW9FViCmp9iSp4njWcad4eglaIiIwA7yzrQNXm9XyvNnzY_7sCYUk7HEEAknpuxogHeoN6ebbI1bcF1ER0ClroUjcnOSVaeEX0y6VCdQNeT22p8wtDtS4uzA_BLGtsduNAoGOQenvOBAC7j_yZv-C8XxvQMJ3h5s44zSj09AFbzUXlMLKgg_gy9373WmxFclsRTZRCeOV2Jln5Q6vXJgIiXYyGOhiowPX9ttuoiD6mtD_qF7JLd2k4r1I78k7CgTHjPmasWFLpv5sjLd8-7-rLcjQAbpZOUmnu-Y_0z9e64cL5s27Xh8NEPlwkPL0vENDdPBVK1kt09OpZLiQ7Ix');

        // Carrega a classe de autenticação
        $authGraphqlRequest = new AuthGraphqlRequest();

        // Recupera as informações do usuário logado
        $authGraphqlRequest->usuarioLogadoInfo();
        Session::forget(ConfigRequest::SESSION_APP_HEADER_NAME);
        Session::forget(ConfigRequest::SESSION_USER_HEADER_NAME);
    }

    /**
     * Testa informações do servidor.
     */
    public function testServerInfo()
    {
        // Carrega a classe de autenticação
        $authGraphqlRequest = new AuthGraphqlRequest();

        // Tenta realizar o login na Conta Institucional
        $serverInfo = $authGraphqlRequest->serverInfo();
        $this->assertNotNull($serverInfo);
        $this->assertEquals($serverInfo->name, 'Microsserviços');
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
        $this->assertNotNull(Session::get(ConfigRequest::SESSION_APP_HEADER_NAME));
        $this->assertNotNull(Session::get(ConfigRequest::SESSION_USER_HEADER_NAME));
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
