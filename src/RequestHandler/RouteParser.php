<?php

namespace App\RequestHandler;

use Psr\Http\Message\RequestInterface;

class RouteParser
{
    public function parseRouteFromUri(RequestInterface $request): Route
    {
        $method = $request->getMethod();

        $requestTargets = explode('/',$request->getRequestTarget());

        if (is_numeric(end($requestTargets))) {
            $resourceId     = end($requestTargets);
            $resourceName   = prev($requestTargets);
        } else {
            $resourceId     = null;
            $resourceName   = end($requestTargets);
        }

        return new Route($resourceId,$resourceName,$method);
    }
}
