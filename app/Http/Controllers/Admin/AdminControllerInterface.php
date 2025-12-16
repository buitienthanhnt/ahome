<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

interface AdminControllerInterface
{
    const CONTROLLER_NAME = 'Admin\AdminController';

    const ADMIN = 'admin';
    const ADMIN_DEFAULT = 'default';
    const ADMIN_HOME = 'home';
    const ADMIN_LOGIN = 'adminLogin';
    const LOGIN_POST = 'loginPost';
    const LOGOUT = 'logout';

    public function home();

    public function adminLogin();

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginPost(Request $request);

    public function logout();

    public function default();
}
