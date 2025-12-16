<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HtmlMinifier
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $contentType = $response->headers->get('Content-Type');
        if (!$request->ajax() && strpos($contentType, 'text/html') !== false) {
            $response->setContent($this->minify($response->getContent()));
        }
        return $response;
    }

    public function minify($input)
    {
        $search = [
            '/\>\s+/s',
            '/\s+</s',
        ];

        $replace = [
            '> ',
            ' <',
        ];
        return preg_replace($search, $replace, $input);
    }
}
