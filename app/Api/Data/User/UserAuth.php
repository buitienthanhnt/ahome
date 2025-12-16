<?php
namespace App\Api\Data\User;

class UserAuth extends UserInfo implements UserAuthInterface
{
    function setToken($token)
    {
        return $this->setData(self::TOKEN, $token);
    }

    function getToken(){
        return $this->getData(self::TOKEN);
    }

    function setRefreshToken($refresh_token)
    {
        return $this->setData(self::REFRESH_TOKEN, $refresh_token);
    }

    function getRefreshToken()
    {
        return $this->getData(self::REFRESH_TOKEN);
    }
}
