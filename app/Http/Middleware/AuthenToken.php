<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Thanhnt\Nan\Helper\TokenManager;

class AuthenToken
{
    protected $user;

    protected $tokenManager;

    private $passUrl = [
        'api/paperMostView',
        'api/test/constEx',
        'api/refreshToken',
        'api/getToken',
    ];

    function __construct(
        User $user,
        TokenManager $tokenManager
    ) {
        $this->user = $user;
        $this->tokenManager = $tokenManager;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\ResponseData|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $path = $request->getPathInfo();
        if (!in_array(trim($path, '/'), $this->passUrl)) {
            $tokenData = (array) $this->tokenManager->getTokenData();
            if (empty($tokenData)) {
                return response([
                    'message' => 'token expire!'
                ], 401);
            }
//            if (!$this->tokenManager->getTimeStartTokenData()) {
//                return response([
//                    'message' => 'request not allow!'
//                ], HttpFoundationResponse::HTTP_NOT_ACCEPTABLE);
//            }
            $tokenData = (array) $tokenData['iss'];
            if (isset($tokenData['sid'])) {
                if (!Session::isStarted()) {
                    Session::start();
                }
                Session::setId($tokenData['sid']);
                Session::start();
            }

            if (isset($tokenData['id'])) {
                if (!Auth::check()){
                    $user = $this->user->find($tokenData['id']);
                    Auth::setUser($user);
                }
            }
        }
        return $next($request);
    }
}
