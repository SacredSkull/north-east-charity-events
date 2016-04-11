<?php
/**
 * Created by PhpStorm.
 * User: Peter
 * Date: 07/04/2016
 * Time: 19:52
 */

namespace NorthEastEvents\Middleware;

use Interop\Container\ContainerInterface;
use NorthEastEvents\Controllers\ResourceInterface;
use NorthEastEvents\Models\User;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use NorthEastEvents\Bootstrap;
use NorthEastEvents\Controllers\UserController;

class AuthorisedRouteMiddleware {
    // TODO: Works, but shouldn't block requests in most cases (just checking if we should return private data or not)
    public function __invoke(Request $request, Response $response, callable $next) {
        $route = $request->getAttribute("route");
        // The route needs to be defined
        $request = $request->withAttribute("authorised", false);
        if($route != null){
            $api = strpos($route->getName(), "API") !== false;
            $args = $route->getArguments();
            if (User::CheckAuthorised($request->getAttribute("current_user", null), $args["userID"] ?: null)) {
                $request = $request->withAttribute("authorised", true);
            }
        }
        $response = $next($request, $response);
        return $response;
    }
}