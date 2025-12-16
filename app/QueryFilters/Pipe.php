<?php

namespace App\QueryFilters;

use Closure;

interface Pipe
{
    /**
     * @param         $before
     * @param Closure $next
     * @return mixed
     */
    public function handle($before, Closure $next);
}
