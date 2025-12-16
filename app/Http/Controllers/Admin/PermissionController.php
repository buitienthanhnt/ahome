<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Rule;
use App\Models\PermissionRules;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;

class PermissionController extends Controller implements PermissionControllerInterface
{
    protected $request;
    protected $router;
    protected $rule;
    protected $permission;
    protected $permissionRules;

    function __construct(
        Router $router,
        Rule $rule,
        Permission $permission,
        PermissionRules $permissionRules,
        Request $request
    )
    {
        $this->router = $router;
        $this->rule = $rule;
        $this->permission = $permission;
        $this->permissionRules = $permissionRules;
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    function listPermission()
    {
        $permission = $this->permission->paginate(8);
        return view("adminhtml/templates/permission/list", ["permissions" => $permission]);
    }

    function createPermission()
    {
        $prefixGroup = $this->allRules();
        return view("adminhtml/templates/permission/create", ["rules" => json_encode($prefixGroup)]);
        // return view("adminhtml/templates/permission/create", ["rules" => json_encode(["Name" => "all", "Number" => 0, "Children" => $rules]), "ul_rules" => $ul_rules]);
    }

    function insertPermission()
    {
        $request = $this->request;
        $params = $request->toArray();
        $rules = array_filter($params, function ($value, $key) {
            if ($value == "on") {
                return true;
            }
        }, ARRAY_FILTER_USE_BOTH);

        $rules = $this->filterRules(array_map(fn($value): String => str_replace("checkbox-", "", $value), array_keys($rules)));
        $new_permission = new Permission(["label" => $params["label"]]);
        $value = $new_permission->save();
        if ($value) {
            $this->permissionRules->insert_permission_rules($new_permission->id, $rules);
            return redirect()->back()->with("success", "add new permission!!");
        }
        return redirect()->back()->with("error", "cant insert permission");
    }

    function allRules() : array {

        $allOfRoute = $this->router->getRoutes();
        $actions = collect($allOfRoute)->map(function($item){
            try {
                $action =  $item->getAction();
                if (strpos($action["prefix"], "adminhtml") !== false) {
                    return $action;
                }
            } catch (\Throwable $th) {
                //throw $th;
            }

        })->filter();
        return $this->actionByController($actions->toArray());
    }

    function actionByController($actions = []) : array {
        if ($actions) {
            $controllerGroups = [];
            $prefixGroups = [];
            foreach ($actions as $action) {
                $prefixGroups[$action["prefix"]][] = [
                    "Name"     => explode("@", $action["controller"], 2)[1],
                    "Number"   => str_replace(["\\", "/", "@"], ["-", "__", "_"], $action["controller"]),
                    "Children" => []
                ];
            }

            $rulesTree = [];
            foreach ($prefixGroups as $key => $value) {
                $rulesTree[] = [
                    "Name"      => str_replace("/", " ", $key),
                    "Number"    => $key,
                    "Children"  => $value
                ];
            }
            return [
                "Name" => "root admin",
                "Number" => "rootAdmin",
                "Children" => $rulesTree
            ];
        }
        return [];
    }

    function filterRules($rules): array{
        if (in_array("rootAdmin", $rules)) {
            return ["rootAdmin"];
        }
        return $rules;
    }

    /**
     * @param int $permission_id
     * @return mixed|void
     */
    function editPermission(int $permission_id) {
        return;
    }

    /**
     * @param int $permission_id
     * @return mixed|void
     */
    function updatePermission(int $permission_id) {
        return;
    }

    function deletePermission() {
        $request = $this->request;
        $permission_id = $request->get("permission_id");
        if ($permission_id) {
            try {
                $permission = $this->permission->find($permission_id);
                $permissionRules = $permission->rulePermission()->getResults();
                foreach ($permissionRules as $rule) {
                    $rule->delete();
                }
                $permission->delete();
                return response(json_encode([
                    "code" => "200",
                    "value" => "deleted: $permission->label success"
                ]), 200);
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
        return response(json_encode([
            "code" => "401",
            "value" => "can not delete, please try again."
        ]), 401);
    }

    /**
     * @param int $permission_id
     * @return \Illuminate\Contracts\View\View|null
     */
    function detailPermission($permission_id): ?\Illuminate\Contracts\View\View{
        $permission = Permission::find($permission_id);
        $prefixGroup = $this->allRules();
        $permissionRules = $permission->allRules();
        return view("adminhtml.templates.permission.detail",
            [
                "permission" => $permission,
                "rules" => json_encode($prefixGroup),
                "rules_selected" => json_encode($permissionRules)
            ]
        );
    }

}
