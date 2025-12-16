<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller implements AdminControllerInterface
{
    //
    protected $adminUser;

    public function __construct(
        AdminUser $adminUser
    ) {
        $this->adminUser = $adminUser;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function home()
    {
        return view("adminhtml/templates/home");
    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function adminLogin()
    {
        if ($this->get_user_admin()) {
            return redirect()->intended("admin");
        }
        return view("adminhtml/templates/adminUser/adminLogin");
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginPost(Request $request)
    {
        if ($this->get_user_admin()) {
            return redirect()->route("admin");
        }
        $request->validate([
            "admin_user" => 'required',
            "admin_password" => 'required'
        ]);

        if ($adminUser = $this->adminUser->admin_login($request->get("admin_user"), $request->get("admin_password"))) {
            $this->admin_login($adminUser);
            return redirect()->intended("adminhtml")->with("success", "login success. Hello $adminUser->name");
        }
        return redirect()->back()->with("error", "can not login. Please check again user name and passsword.");
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        if ($this->adminUser->check_login()) {
            $this->adminUser->admin_logout();
            return redirect()->route("admin_login")->with("success", "logout! see you again.");
        } else {
            return redirect()->route("admin")->with("error", "can not logout, please login first!!");
        }
    }

    protected function admin_login($adminUser)
    {
        if (!Session::isStarted()) {
            Session::start();
        }
        Session::push("admin_user", $adminUser);
        Session::save();
    }

    protected function get_user_admin()
    {
        if (!Session::isStarted()) {
            Session::start();
        }
        return Session::get("admin_user", null);
    }

    public function default()
    {
        return view("adminhtml/templates/default");
    }
}
