<?php
namespace NorthEastEvents;

use Interop\Container\ContainerInterface;
use NorthEastEvents\Map\UserTableMap;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class UserController extends Controller {
    public function isCurrentUserAuthorised($user){
        $current = Bootstrap::getLoginSession()->get("user", null);
        if($current == null)
            return false;
        if($current->isAdmin())
            return true;

        if(is_a($user, 'User')){
            // Assume this is a User object
            if($current->getId() === $user->getId())
                return true;
        } else {
            // User ID
            if($current->getId() == $user)
                return true;
        }
        return false;
    }

    public function UserHandler(Request $request, Response $response, $args) {
        $usersQuery = UserQuery::create();
        $user = null;

        if(isset($args['id']))
            $user = $usersQuery->findOneById($args['id']);
        else {
            $current = Bootstrap::getLoginSession()->get("user", null);
            if(!$current == null) {
                $user = $current;
            } else {
                return $response->withJson(array("Error" => ["Message" => "Login to view your own data."]), 403);
            }
        }

        // This user doesn't exist
        if ($user == null) {
            return $response->withJson(array("Error" => ["Message" => "User does not exist"]), 404);
        } else if ($request->isGet()) {
            // JSON example, with sensitive:
            //{"Id":7,"Username":"edavis","Password":"$2y$10$SgwNlcG5kMJt35E34EiHIObrj7BfhXjcWGOFZFUuLtU","Email":"cook.elliot@kelly.com","FirstName":"Ryan","LastName":"Hunter","AvatarUrl":"http:\/\/lorempixel.com\/640\/480\/?51557","Permission":"staff","CreatedAt":"2016-03-19T03:14:08+00:00","UpdatedAt":"2016-03-19T03:14:08+00:00"}
            // Public view:
            //{"Id":7,"Username":"edavis","AvatarUrl":"http:\/\/lorempixel.com\/640\/480\/?51557","Permission":"1"}
            // Same format, which is great! All that is needed is a check if user is logged in && query_user == current_user to return sensitive.

            if($this->isCurrentUserAuthorised($user)){
                $user = $usersQuery->select(['Id', 'Username', 'Email', 'FirstName', 'LastName', 'AvatarUrl', 'Permission'])->findOneById($user->getId());
                return $response = $response->withJson(['User' => $user]);
            }
            $user = $usersQuery->select(['Id', 'Username', 'AvatarUrl', 'Permission'])->findOneById($user->getId());
            return $response = $response->withJson(['User' => $user]);
        } else if ($request->isDelete()) {
            // TODO: Should the API provide this?
            return $response = $response->withJson(['Error' => ["Message" => "Deleting user accounts is not currently permissible using the API."]], 405);
        } else if ($request->isPut() || $request->isPatch()) {
            if($this->isCurrentUserAuthorised($user)) {
                $userjson = $request->getParsedBody()['user'];
                $userjson = json_decode($userjson, true);
                if (array_key_exists('FirstName', $userjson))
                    $user->setFirstName($userjson['FirstName']);
                if (array_key_exists('LastName', $userjson))
                    $user->setLastName($userjson['LastName']);
                if (array_key_exists('AvatarUrl', $userjson))
                    $user->setAvatarUrl($userjson['AvatarUrl']);
                if ($user->isAdmin() && array_key_exists('Permission', $userjson)) {
                    if (stripos('normal', $userjson['Permission']) !== FALSE)
                        $user->setAvatarUrl(\NorthEastEvents\Map\UserTableMap::COL_PERMISSION_NORMAL);
                    if (stripos('staff', $userjson['Permission']) !== FALSE)
                        $user->setAvatarUrl(\NorthEastEvents\Map\UserTableMap::COL_PERMISSION_STAFF);
                }
                return $response->withJson([ "Success" => "The user was successfully modified.", "User" => [ $user->getId(), $user->getUsername(), $user->getAvatarUrl(), $user->getPermission() ]]);
            } else {
                return $response->withJson([ "Error" => ["Message" => "You do not have to permission to modify this user."]], 403);
            }
        }
    }

    public function getUsers(Request $request, Response $response) {
        $users = UserQuery::create();
        return $this->ci->get("view")->render($response, "example.twig.html", [
            // 'name' => $arg['name']]
            'template_name' => "User Example",
            'users' => $users,
            'variable_name' => "Twig variable example"
        ]);
    }

    public function APIGetUsers(Request $request, Response $response) {
        $users = UserQuery::create()->select(['Id', 'Username', 'AvatarUrl', 'Permission'])->find();
        return $response->withJson($users->getData());
    }

    public function LoginSession($username, $password = null){
        $user = null;
        if(is_a($username, '\NorthEastEvents\User')){
            $user = $username;
        } else if (strlen($username) == 0 || strlen($password) == 0 || $username == null || $password == null) {
            return ["Error" => ["Message" => "Username and password must be given."]];
        } else {
            if(!User::CheckLogin($username, $password)) {
                Bootstrap::getLogger()->addDebug(sprintf("[DEBUG][AUTH] Bad details given for (%s) using password (%)", $username, $password));
                return ["Error" => ["Message" => "Incorrect login details."]];
            }

            $user = UserQuery::create()->findOneByEmail($username);
            if($user == null)
                $user = UserQuery::create()->findOneByUsername($username);
            if($user == null){
                Bootstrap::getLogger()->addError(sprintf("[ERROR][AUTH] Login was said to be valid, but no user with %s could be found with that username/email."), $username);
                return ["Error" => ["Message" => "A miscellaneous error has occurred and has been reported."]];
            }
        }
        Bootstrap::getLogger()->addDebug(sprintf("[DEBUG][AUTH] Logged in user %s", $user->getUsername()));
        $segment = Bootstrap::getSession()->getSegment('NorthEastEvents\Login');
        $user_session = $segment->get('user', null);
        if($user_session != null){
            // User was logged in before, invalidate previous session.
            Bootstrap::getSession()->clear();
            Bootstrap::getSession()->regenerateId();
        }
        $segment->set('user', $user);
        return ["Session" => session_id()];
    }

    public function LoginController(Request $request, Response $response) {
        $username = isset($request->getParsedBody()['username'])? $request->getParsedBody()['username'] : null;
        $password = isset($request->getParsedBody()['password'])? $request->getParsedBody()['password'] : null;
        $this->LoginSession($username, $password);
        return $response->withJson(["Success" => "Logged in successfully."]);
    }

    public function APILoginController(Request $request, Response $response) {
        $username = isset($request->getParsedBody()['username'])? $request->getParsedBody()['username'] : null;
        $password = isset($request->getParsedBody()['password'])? $request->getParsedBody()['password'] : null;
        return $response->withJson($this->LoginSession($username, $password));
    }

    public function CreateUser(Request $request, Response $response)
    {
        $username = $request->getParsedBody()['username'];
        $password = $request->getParsedBody()['password'];
        $email = $request->getParsedBody()['email'];
        $fname = isset($request->getParsedBody()['first_name'])? $request->getParsedBody()['first_name'] : null;
        $lname = isset($request->getParsedBody()['last_name'])? $request->getParsedBody()['last_name'] : null;
        $avatar = isset($request->getParsedBody()['avatar'])? $request->getParsedBody()['avatar'] : null;

        if (isset($username) && UserQuery::create()->findOneByUsername($username) != null)
            return $response->withJson(["Error" => ["Message" => "Username has been taken."]]);
        if (isset($email) && UserQuery::create()->findOneByEmail($email) != null)
            return $response->withJson(["Error" => ["Message" => "Email is in use."]]);
        if($password == null || strlen($password) < 4)
            return $response->withJson(["Error" => ["Message" => "Passwords must be larger than 4 characters."]]);

        $user = new User();
        $user->setUsername($username);
        $user->setPassword($password);
        $user->setEmail($email);

        if (isset($fname))
            $user->setFirstName($fname);
        if (isset($lname))
            $user->setLastName($lname);
        if (isset($avatar))
            $user->setAvatarUrl($avatar);
        $user->save();

        $userData = UserQuery::create()->select(['Id', 'Username', 'Email', 'FirstName', 'LastName', 'AvatarUrl', 'Permission'])->findOneById($user->getId());

        return $response->withJson(["User" => $userData, "Session" => $this->LoginSession($user)], 201);
    }
}

// All users
$app->get('/users', '\NorthEastEvents\UserController:GetUsers');

// Login
$app->post('/user/login', '\NorthEastEvents\UserController:LoginController');
//TODO: Logout
$app->post('/user/logout', '\NorthEastEvents\UserController:LogoutController');

$app->group('/api', function () use ($app) {
    $app->get('/users', '\NorthEastEvents\UserController:APIGetUsers');
    $app->post('/user/register', '\NorthEastEvents\UserController:CreateUser')->setName("APIUserCreate");
    $app->post('/user/login', '\NorthEastEvents\UserController:APILoginController')->setName("APIUserLogin");
    //TODO: Logout
    $app->post('/user/logout', '\NorthEastEvents\UserController:APILogoutController')->setName("APIUserLogout");
    $app->post('/user', '\NorthEastEvents\UserController:CreateUser')->setName("APIUserCreate");
    //TODO: Create GET method for /user to pull up the current user's details (i.e. omit the user ID)
    $app->get('/user', '\NorthEastEvents\UserController:UserHandler')->setName("APIUserGET");;
    $app->map(["GET", "DELETE", "PUT", "PATCH"], '/user/{id:[0-9]+}', '\NorthEastEvents\UserController:UserHandler')->setName("APIUserOperations");
});
