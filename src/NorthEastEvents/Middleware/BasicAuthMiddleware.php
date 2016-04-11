<?php

namespace NorthEastEvents\Middleware;

use NorthEastEvents\Models\User;
use NorthEastEvents\Models\UserQuery;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class BasicAuthMiddleware {
    public function __invoke(Request $request, Response $response, callable $next) {
        $details = explode(":", $request->getUri()->getUserInfo(), 2);
        $request->withAttribute("current_user", null);
        if(sizeof($details) == 2) {
            if (!User::CheckLogin($details[0], $details[1])) {
                return $response->withJson(["Error", ["Message" => "Bad login details."]], 403);
            }
            $request = $request->withAttribute("current_user", UserQuery::create()->findOneByUsername($details[0]));
        }
        $response = $next($request, $response);
        return $response;
    }
}