<?php

namespace App\Api\Convert;

use App\Api\Data\User\UserAuth;
use App\Api\Data\User\UserInfo;
use App\Models\User;
use Thanhnt\Nan\Helper\TokenManager;

class ConvertUser
{

    protected $userInfo;
    protected $userAuth;

    protected $tokenManager;

    function __construct(
        UserInfo $userInfo,
        UserAuth $userAuth,
        TokenManager $tokenManager
    ) {
        $this->userInfo = $userInfo;
        $this->userAuth = $userAuth;
        $this->tokenManager = $tokenManager;
    }

    function convertUserInfo(User $user): UserInfo
    {
        $userInfo = $this->userInfo;
        $userInfo->setName($user->name);
        $userInfo->setEmail($user->email);
        $user->setCreatedAt($user->created_at);
        return $userInfo;
    }

    function convertUserAuth(User $user)
    {
        $userAuth = $this->userAuth;
        $userAuth->setName($user->name);
        $userAuth->setEmail($user->email);
        $userAuth->setCreatedAt($user->created_at);
        $userAuth->setToken($this->tokenManager->getToken($user->toArray()));
        $userAuth->setRefreshToken($this->tokenManager->getRefreshToken($user->toArray()));
        return $userAuth;
    }
}
