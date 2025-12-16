<?php

namespace App\QueryFilters;

use Closure;
use Illuminate\Http\Request;
use Str;

class Sort implements Pipe
{
    public function handle($before, Closure $next)
    {
        $filterParam = Str::snake(class_basename($this));

        if (!request()->has($filterParam)){
            return $next($before);
        }
        // trường hợp cuối thì cũng không cần gọi action này
        $builder = $next($before);

        return $builder->orderBy('title', request($filterParam));
    }
}
