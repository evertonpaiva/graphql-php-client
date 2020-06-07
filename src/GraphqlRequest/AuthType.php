<?php

namespace GraphqlClient\GraphqlRequest;

abstract class AuthType
{
    const NO_AUTH = 'NO_AUTH';
    const APP_AUTH = 'APP_AUTH';
    const APP_USER_AUTH = 'APP_USER_AUTH';
}
