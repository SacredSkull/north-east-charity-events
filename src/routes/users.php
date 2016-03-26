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
        return $response->withJson($users);
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
        $usersQuery = \NorthEastEvents\Base\UserQuery::create();
        $user = $usersQuery->findOneById($args['id']);

        // This user doesn't exist
        if($user == null){
            $response->withStatus(404)->getBody()->write('{"Error": "User does not exist"}');
        }

        // This user does...
        if($request->isGet()) {
            // TODO: If the client is requesting their own information this should all valid info (e.g. email, but never password)
            // JSON example, with sensitive:
            //{"Id":7,"Username":"edavis","Password":"$2y$10$SgwNlcG5kMJt35E34EiHIObrj7BfhXjcWGOFZFUuLtU","Email":"cook.elliot@kelly.com","FirstName":"Ryan","LastName":"Hunter","AvatarUrl":"http:\/\/lorempixel.com\/640\/480\/?51557","Permission":"staff","CreatedAt":"2016-03-19T03:14:08+00:00","UpdatedAt":"2016-03-19T03:14:08+00:00"}
            // Public view:
            //{"Id":7,"Username":"edavis","AvatarUrl":"http:\/\/lorempixel.com\/640\/480\/?51557","Permission":"1"}
            // Same format, which is great! All that is needed is a check if user is logged in && query_user == current_user to return sensitive.
            $user = $usersQuery->select(['Id', 'Username', 'AvatarUrl', 'Permission'])->findOneById($args['id']);
            $response = $response->withJson($user);
        }
        
        if($request->isDelete()) {
            // TODO: Make sure user is SIGNED IN!
            $user->delete();
            $response->getBody()->write("User deleted? ".  ($user->isDeleted()? "Yes" : "No"));
        }
        if($request->isPost() || $request->isPut() || $request->isPatch()){
            /* TODO: Check if this request is for modifying an existing user, and if so, is the current client logged in as that user or has admin privileges? Make sure to return an securely ambiguous error to prevent  probing. */
            // TODO: If not, assume this is a new registration. Check that the email is not in use; same securely ambiguous error otherwise.
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
    })->setName("User_API_REST");
});
