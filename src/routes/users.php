<?php

use NorthEastEvents\User;
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

    $app->post('/user', function (Request $request, Response $response) {
        $user = new User();
        $userjson = $request->getParsedBody()['user'];
        $userjson = json_decode($userjson, true);
        if(array_key_exists('FirstName', $userjson))
            $user->setFirstName($userjson['FirstName']);
        if(array_key_exists('LastName', $userjson))
            $user->setLastName($userjson['LastName']);
        if(array_key_exists('AvatarUrl', $userjson))
            $user->setAvatarUrl($userjson['AvatarUrl']);
        if(array_key_exists('Permission', $userjson)) {
            if(stripos('normal', $userjson['Permission']) !== FALSE)
                $user->setAvatarUrl(\NorthEastEvents\Map\UserTableMap::COL_PERMISSION_NORMAL);
            if(stripos('staff', $userjson['Permission']) !== FALSE)
                $user->setAvatarUrl(\NorthEastEvents\Map\UserTableMap::COL_PERMISSION_NORMAL);
        }
        $user->save();
    });

    $app->map(["GET", "DELETE", "PUT", "PATCH", "POST"], '/user/{id:[0-9]+}', function (Request $request, Response $response, $args) use ($app) {
        $user = \NorthEastEvents\Base\UserQuery::create()->findOneById($args['id']);
        if($request->isGet())
            $response->getBody()->write($user->toJSON());
        if($request->isDelete()) {
            $user->delete();
            $response->getBody()->write("User deleted? ".  ($user->isDeleted()? "Yes" : "No"));
        }
        if($request->isPost() || $request->isPut() || $request->isPatch()){
            $userjson = $request->getParsedBody()['user'];
            $userjson = json_decode($userjson, true);
            if(array_key_exists('FirstName', $userjson))
                $user->setFirstName($userjson['FirstName']);
            if(array_key_exists('LastName', $userjson))
                $user->setLastName($userjson['LastName']);
            if(array_key_exists('AvatarUrl', $userjson))
                $user->setAvatarUrl($userjson['AvatarUrl']);
            if(array_key_exists('Permission', $userjson)) {
                if(stripos('normal', $userjson['Permission']) !== FALSE)
                    $user->setAvatarUrl(\NorthEastEvents\Map\UserTableMap::COL_PERMISSION_NORMAL);
                if(stripos('staff', $userjson['Permission']) !== FALSE)
                    $user->setAvatarUrl(\NorthEastEvents\Map\UserTableMap::COL_PERMISSION_STAFF);
            }
        }

        return $response;
    });
});
