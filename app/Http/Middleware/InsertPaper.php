<?php

namespace App\Http\Middleware;

use App\Models\PaperInterface;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Thanhnt\Nan\Helper\StringHelper;

class InsertPaper
{
    use StringHelper;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\ResponseData|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->get(PaperInterface::ATTR_TITLE)) {
            return redirect()->back()->with("error", "Page title is require!");
        }

        if (
            $request->isMethod("POST") &&
            $this->checkUrlAliasExist($this->formatPath($request->get(PaperInterface::ATTR_URL_ALIAS) ?: $request->get(PaperInterface::ATTR_TITLE)))
        ) {
            return redirect()->back()->with("error", "Page [url alias] is exist!");
        }

        return $next($request);
    }

    function checkUrlAliasExist(string $url_alias): bool
    {
        if (DB::table(PaperInterface::TABLE_NAME)->where(PaperInterface::ATTR_URL_ALIAS, $url_alias)->count()) {
            return true;
        }
        return false;
    }
}
