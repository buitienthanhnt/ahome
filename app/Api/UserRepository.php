<?php
namespace App\Api;

use App\Api\Data\User\UserAuth;
use App\Api\Data\User\UserInfo;
use App\Models\User;
use Thanhnt\Nan\Helper\TokenManager;

class UserRepository{

    protected $userInfo;
    protected $userAuth;

    protected $tokenManager;

    function __construct(
        UserInfo $userInfo,
        UserAuth $userAuth,
        TokenManager $tokenManager
    )
    {
        $this->userInfo = $userInfo;
        $this->userAuth = $userAuth;
        $this->tokenManager = $tokenManager;
    }

}
