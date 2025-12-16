<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class AdminPermission
{
    /**
     * Handle an incoming request.
     * SignOut
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\ResponseData|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->check_login()) {
            if (!$this->checPermission($request)) {
                return redirect()->route("admin")->with("error", "you not has permission to resource!");
            }
            return $next($request);
        }
        return redirect()->route("admin_login")->with("error", "please login first!");
    }

    public function check_login(): bool
    {
        if (!Session::isStarted()) {
            Session::start();
        }
        return Session::has("admin_user");
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|void
     */
    public function checPermission(Request $request)
    {
        // admin_user (quản trị viên) =>
        //           admin_user_permission ( các quyền quản trị viên) =>
        //                                 permission (thông tin quyền) =>
        //                                            permission_rules(các quy tắc router gốc)
        $userAdmin = Session::get("admin_user");
        $userAdminPermissions = $userAdmin[0]->getPermissionRules();
        if (in_array("rootAdmin", $userAdminPermissions)) {
            return true;
        } elseif (in_array($request->route()->getAction()["controller"], $userAdminPermissions)) {
            return true;
        }
        return false;
    }
}
