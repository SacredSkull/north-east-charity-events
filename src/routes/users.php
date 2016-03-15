<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// All users
$app->get('/users', function (Request $request, Response $response) {
    $users = \NorthEastEvents\Base\UserQuery::create();
    return $this->get("view")->render($response, "example.twig.html", [
        // 'name' => $arg['name']]
        'template_name' => "User Example",
        'users' => $users,
        'variable_name' => "Twig variable example"
    ]);
});

$app->group('/api', function() use ($app) {
    $app->get('/users', function (Request $request, Response $response) {
        $users = \NorthEastEvents\Base\UserQuery::create()->find();
        $response->getBody()->write($users->toJSON());
        return $response;
    });

    $app->map(["GET", "DELETE", "PUT", "PATCH"], '/user/{id:[0-9]+}', function (Request $request, Response $response, $args) use ($app) {
        $user = \NorthEastEvents\Base\UserQuery::create()->findOneById($args['id']);
        if($request->isGet())
            $response->getBody()->write($user->toJSON());
        if($request->isDelete()) {
            $user->delete();
            $response->getBody()->write("User deleted? ".  ($user->isDeleted()? "Yes" : "No"));
        }
        if($request->isPost() || $request->isPut() || $request->isPatch()){
            $userjson = $_POST["user"];
            $userjson = json_decode($userjson, true);
             
        }

        return $response;
    });
});
