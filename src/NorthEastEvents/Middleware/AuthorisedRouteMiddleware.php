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
    public function __invoke(Request $request, Response $response, callable $next) {
        $route = $request->getAttribute("route");
        // The route needs to be defined
        $request = $request->withAttribute("authorised", false);
        if($route != null){
            $args = $route->getArguments();
            $current_user = $request->getAttribute("current_user", null);
            if (User::CheckAuthorised($current_user, $args["userID"] ?? $current_user)) {
                $request = $request->withAttribute("authorised", true);
            }
        }
        $response = $next($request, $response);
        return $response;
    }
}