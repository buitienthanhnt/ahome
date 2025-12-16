<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rule;
use Illuminate\Http\Request;

class RuleController extends Controller
{
    protected $rule;
    protected $router;

    function __construct(
        Rule $rule,
        \Illuminate\Routing\Router $router
    ) {
        $this->rule = $rule;
        $this->router = $router;
    }

    function allRules()
    {
        $allOfRoute = $this->router->getRoutes();
        $actions = collect($allOfRoute)->map(function ($item) {
            $action =  $item->getAction();
            if (isset($action["prefix"]) && strpos($action["prefix"], "adminhtml") !== false) {
                return $action;
            }
        })->filter();
        $prefixGroup = $this->actionByController($actions->toArray());
        dd($prefixGroup);
        return;
    }

    function actionByController($actions = []): array
    {
        if ($actions) {
            $controllerGroups = [];
            $prefixGroups = [];
            foreach ($actions as $action) {
                $prefixGroups[$action["prefix"]][] = [
                    "Name"     => explode("@", $action["controller"], 2)[1],
                    "Number"   => $action["controller"],
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
                "Number" => "root_admin",
                "Children" => $rulesTree
            ];
        }
        return [];
    }

    /**
     * Compile the routes into a displayable format.
     *
     * @return array
     */
    protected function getRoutes($router)
    {
        $routes = collect($router->getRoutes())->map(function ($router) {
            return $this->getRouteInformation($router);
        })->filter()->all();

        if (($sort = $this->option('sort')) !== 'precedence') {
            $routes = $this->sortRoutes($sort, $routes);
        }

        if ($this->option('reverse')) {
            $routes = array_reverse($routes);
        }

        return $this->pluckColumns($routes);
    }

    function list()
    {
        $all_rules = Rule::paginate(8);
        return view("adminhtml/templates/rule/list", ["rules" => $all_rules]);
    }

    function create()
    {
        $rules = Rule::all();
        $rules_option = $this->rule->rule_tree_option();
        return view("adminhtml/templates/rule/create", ["rules" => $rules, "rules_option" => $rules_option]);
    }

    function insert(Request $request)
    {
        // phpinfo();
        // dd($request->toArray());
        try {
            $rule = new Rule($request->toArray() + ["parent_id" => $request->get("parent_id", 0)]);
            $save_status = $rule->save();
        } catch (\Throwable $th) {
            return redirect()->back()->with("error", $th->getMessage());
        }

        if ($save_status) {
            return redirect()->back()->with("success", "created new rule!");
        }
        return redirect()->back()->with("error", "can not create rule right now!");
    }

    function edit(Request $request)
    {
        $parent = null;
        $rule = Rule::find($request->get("id"));
        if ($parent_id = $request->get("parent_id")) {
            $parent = Rule::find($parent_id);
        }

        $children = $rule->getChildren();
        return view("adminhtml/templates/rule/edit", ["rule" => $rule, "parent" => $parent, "children" => $children]);
    }

    function delete(Request $request)
    {
        try {
            $rule_id = $request->get("rule_id");
            if ($rule = Rule::find($rule_id)) {
                $rule->delete();
                return response(json_encode([
                    "code" => "200",
                    "value" => "deleted: success!"
                ]), 200);
            }
            return response(json_encode([
                "code" => 401,
                "value" => "delete error. Please try again!"
            ]), 401);
        } catch (\Throwable $th) {
            //throw $th;
        }
        return response(json_encode([
            "code" => 401,
            "value" => "delete error. Please try again!"
        ]), 401);
    }

    function addChildren($parent_id = null, Request $request)
    {
        if ($request->method() == "GET") {
            if ($parent_id) {
                $parent = Rule::find($parent_id);
                return view("adminhtml/templates/rule/addChildren", ["parent" => $parent]);
            }
        } else if ($request->method() == "POST") {
            $save_status = $this->save_rule($request);
            if ($save_status) {
                return redirect()->back()->with("success", "add children rule: ");
            } else {
                return redirect()->back()->with("error", "error for add new children!");
            }
        }

        return redirect()->back()->with("error", "can not add new children now!");
    }

    /**
     * save new rule
     * @return bool
     */
    protected function save_rule(Request $request)
    {
        try {
            $rule = new Rule($request->toArray() + ["parent_id" => $request->get("parent_id", 0)]);
            $save_status = $rule->save();
        } catch (\Throwable $th) {
            $save_status = false;
        }
        return $save_status;
    }
}
