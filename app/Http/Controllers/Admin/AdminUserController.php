<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use App\Models\AdminUserInterface;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller implements AdminUserControllerInterface
{
    protected $request;
    protected $adminUser;

    function __construct(Request $request, AdminUser $adminUser)
    {
        $this->request = $request;
        $this->adminUser = $adminUser;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    function listAdminUser()
    {
        $allAdminUser = AdminUser::paginate(8);
        return view("adminhtml.templates.adminUser.list", ["allUser" => $allAdminUser]);
    }

    function createAdminUser()
    {
        return view("adminhtml/templates/adminUser/create");
    }

    function insertAdminUser()
    {
        $request = $this->request;
        $adminUser = $this->adminUser;
        $adminUser->fill([
            "name" => $request->get("admin_user"),
            "email" => $request->get("admin_email"),
            "password" => Hash::make($request->get("admin_password")),
            "active" => 0
        ]);
        $res = $adminUser->save();
        if ($res) {
            return redirect()->back()->with("success", "add new adminUser");
        } else {
            return redirect()->back("error", "can`t not save adminUser, please check your input data");
        }
    }

    /**
     * https://phpgrid.com/blog/time-to-use-php-return-types-in-your-code/
     * https://www.php.net/manual/en/language.types.declarations.php
     *
     * @param integer $admin_user_id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function editAdminUser($admin_user_id)
    {
        $user = AdminUser::findOrFail($admin_user_id);
        $allPermission = Permission::all();
        $userPermissions = $user->getPermissionsIds();
        return view("adminhtml.templates.adminUser.edit", ["user" => $user, "permissions" => $allPermission, "userPermissions" => $userPermissions]);
    }

    /**
     * https://phpgrid.com/blog/time-to-use-php-return-types-in-your-code/
     * https://www.php.net/manual/en/language.types.declarations.php
     *
     * @param integer $user_id
     * @param Request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function updateAdminUser($user_id)
    {
        $request = $this->request;
        $user = AdminUser::findOrFail($user_id);
        $user->{AdminUserInterface::ATTR_NAME} = $request->get("admin_user");
        $user->save();
        if ($permisssions = $request->get("permission_values")) {
            $this->adminUser->savePermissions($user, $permisssions);
        }
        return redirect()->back()->with([
            "success" => "updated the user data"
        ]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function deleteAdminUser()
    {
        $adminUser = AdminUser::find($$this->request->get("user_id"));
        if ($adminUser) {
            $adminUser->delete();
            return response(
                json_encode([
                    "message" => "deleted the user",
                    "code" => 200
                ]), 200
            );
        }

        return response(
            json_encode([
                "message" => "can`t delete the user",
                "code" => 405
            ]), 405
        );
    }
}
