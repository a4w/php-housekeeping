<?php

namespace App\Middleware;

use Housekeeping\Routing\Middleware\Middleware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnlyMiddleware extends Middleware
{
    public function __invoke(Request $request): Response
    {
        // Get super secret token
        if ($request->query->get('token', null) === 'hunter11') {
            $request->attributes->set('authenticated', true);
            $response = $this->next($request);
            // After calling next, we can change the response
            $response->headers->set('Content-Type', 'text/plain');
            return $response;
        } else {
            // You are not an admin :(
            return new Response('You are not authenticated', 402);
        }
    }
}
