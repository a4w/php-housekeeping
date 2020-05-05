<?php

namespace Housekeeping\Routing\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Middleware
{
    protected $next;

    /**
     * @param Middleware|Closure $next
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

    /**
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        return $this->next($request);
    }
};
