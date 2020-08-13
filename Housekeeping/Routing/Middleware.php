<?php

namespace Housekeeping\Routing\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Middleware
{
    /** @var Middleware|Callable */
    protected $next;

    /**
     * @param Middleware|Callable $next
     */
    public function setNextMiddleware($next)
    {
        $this->next = $next;
    }

    protected function next(Request $request): Response
    {
        $next = $this->next;
        return $next($request);
    }
}
