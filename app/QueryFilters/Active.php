<?php

namespace App\QueryFilters;

use Closure;
use Illuminate\Http\Request;
use Str;

class Active implements Pipe
{
    public function handle($before, Closure $next)
    {
        $filterParam = Str::snake(class_basename($this));

        if (!request()->has($filterParam)){
            return $next($before);
        }
        // gọi vào các method trước khi chạy action hiện tại.
        $builder = $next($before);

        return $builder->where('show', request($filterParam));
    }
}
