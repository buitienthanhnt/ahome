<?php

namespace App\Api;

use App\Api\Convert\ConvertUser;
use App\Api\Data\ResponseData;
use App\Api\Data\User\UserInfo;
use App\Http\Exception\FormValidationException;
use App\Http\Validation\RegisterUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserApi
{
    protected $request;

    protected $user;
    protected $convertUser;

    protected $registerUser;

    protected $responseData;
    protected $responseApi;

    function __construct(
        Request $request,
        User $user,
        ConvertUser $convertUser,
        ResponseData $responseData,
        ResponseApi $responseApi,
        RegisterUser $registerUser
    ) {
        $this->request = $request;
        $this->registerUser = $registerUser;
        $this->user = $user;
        $this->response = $responseData;
        $this->convertUser = $convertUser;
        $this->responseApi = $responseApi;
    }

    function getUserInfo()
    {
        $user = Auth::user();
        if ($user) {
            /**
             * @var UserInfo $userInfo
             */
            $userInfo = $this->convertUser->convertUserInfo($user);
            return  $this->response->setResponse($userInfo);
            return response($userInfo, 200);
        }
        $this->response->setMessage('you are not logined, please login to continue process.');
        return $this->responseApi->setResponse($this->response)->setStatusCode(402);
    }

    function logIn()
    {

        $request = $this->request;
        $email = $request->get('email');
        $password = $request->get('password');
        if (!($email && $password)) {
            $this->response->setMessage("invalid email or password");
            return $this->responseApi->setResponse($this->response)->setStatusCode(400);
        }
        if (Auth::check()) {
            $this->response->setMessage("người dùng đã đăng nhập, không thể thực hiện thêm!");
            return $this->responseApi->setResponse($this->response)->setStatusCode(403);
        }

        if (Auth::attempt([
            'email' => $email,
            "password" => $password
        ])) {
            $user = $this->user->where("email", $email)->first();
            Auth::login($user);
            $userData = $user->toArray();
            $userData["sid"] = Session::getId();

            return $this->responseApi->setResponse($this->response->setResponse($this->convertUser->convertUserAuth($user)));
        }
        $this->response->setMessage("login fail, error email or password");
        return $this->responseApi->setResponse($this->response)->setStatusCode(400);
    }

    function registerUser()
    {
        $request = $this->request;
        try {
            $this->registerUser->validate($request->all());
            // dd($this->request->all());
            $user = $this->user;
            $user->name = $request->get('name');
            $user->email = $request->get('email');
            $user->password = Hash::make($this->request->get("password"));
            $res = $user->save();
            return $this->responseApi->setResponse($this->response->setResponse($this->convertUser->convertUserAuth($user)));
        } catch (FormValidationException $e) {
            return $this->responseApi->setStatusCode(400)->setResponse($this->response->setMessage($e->getFullMessage()));
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
