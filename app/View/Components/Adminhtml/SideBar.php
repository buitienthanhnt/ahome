<?php

namespace App\View\Components\Adminhtml;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\View\Component;

class SideBar extends Component
{
    const ADMIN_PREFIX = 'adminhtml';

    protected $request;
    protected $router;

    /**
     * Create a new component instance.
     */
    public function __construct(
        Request $request,
        \Illuminate\Routing\Router $router
    ) {
        $this->request = $request;
        $this->router = $router;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $currentPath = $this->request->path();
        return view('components.adminhtml.layouts.components.side-bar', [
            'currentRouter' => $this->request->route(),
            'listRouter' => $this->sibarRouter()
        ]);
    }

    protected function sibarRouter()
    {
        /**
         * list of admin route(include GET method routes and exclude routes has parameter).
         * @var \Illuminate\Routing\Route[] $listAdminRouters
         */
        $listAdminRouters = [];
        foreach ($this->router->getRoutes()->getRoutesByMethod()['GET'] as $key => $value) {
            if (
                strpos($key, self::ADMIN_PREFIX) === 0 &&
                count($value->parameterNames()) === 0 && count($value->bindingFields()) &&
                isset($value->bindingFields()['show']) && $value->bindingFields()['show']
            ) {
                $listAdminRouters[] = $value;
            }
        }
        // dd($listAdminRouters, $listAdminRouters[1]->bindingFields());
        return $listAdminRouters;
    }
}
