<?php
namespace App\Api\Data\User;

interface UserAuthInterface extends UserInfoInterface{
    const TOKEN = 'token';
    const REFRESH_TOKEN = 'refreshToken';

    /**
     * @param mixed $token
     */
    function setToken($token);

    /**
     * @return mixed
     */
    function getToken();

    /**
     * @param mixed $refresh_token
     */
    function setRefreshToken($refresh_token);

    /**
     * @return mixed
     */
    function getRefreshToken();
}