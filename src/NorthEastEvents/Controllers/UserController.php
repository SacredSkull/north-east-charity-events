<?php
namespace NorthEastEvents\Controllers;

use Interop\Container\ContainerInterface;
use NorthEastEvents\Middleware\AuthorisedRouteMiddleware;
use NorthEastEvents\Middleware\BasicAuthMiddleware;
use NorthEastEvents\Models\Map\UserTableMap;
use NorthEastEvents\Models\User;
use NorthEastEvents\Models\UserQuery;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use NorthEastEvents\Bootstrap;
use Slim\App;

class UserController extends Controller {
    const USERS_PER_PAGE = 12;

    // TODO: Separate all controller functions into (protected)Base/API/Front versions - put base functionality in base and api in api, and front in front!
    public $resource_type = "User";
    public $not_allowed_message = "You do not have permission to modify this user.";
    public $not_found_message = "A user could not be found with the information provided.";
    public $generic_error = "An error occurred attempting to interact with this User.";

    public function UserOperations(Request $request, Response $response, $args){
        $usersQuery = UserQuery::create();
        $user = null;

        /** @var User $currentUser */
        $currentUser = $this->current_user;

        if(isset($args['userID']))
            $user = $usersQuery->findOneById($args['userID']);
        else {
            if(!$currentUser == null) {
                $user = $currentUser;
            } else {
                return $this->Unauthorised("You must login to view your own data.", $request, $response, $args);
            }
        }

        // This user doesn't exist
        if ($user == null) {
            $this->NotFound(null, $request, $response, $args);
        } else if ($request->isGet()) {
            if(User::CheckAuthorised($currentUser, $user)) {
                $user = $usersQuery->select(['Id', 'Username', 'Email', 'FirstName', 'LastName', 'AvatarUrl', 'Permission'])->findOneById($user->getId());

                return $this->render($request, $response, "/events/user.html.twig", [
                    'user' => $user,
                ]);
            }
            $user = $usersQuery->select(['Id', 'Username', 'AvatarUrl', 'Permission'])->findOneById($user->getId());
            return $this->render($request, $response, "/events/user.html.twig", [
                'user' => $user,
            ]);
        } else if ($request->isDelete()) {
            // TODO: Should the API provide this?
            return $response = $response->withJson(['Error' => ["Message" => "Deleting user accounts is not currently permissible using the API."]], 405);
        } else if ($request->isPut() || $request->isPatch()) {
            if($currentUser->isAuthorised($user)) {
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
                        $user->setAvatarUrl(UserTableMap::COL_PERMISSION_NORMAL);
                    if (stripos('staff', $userjson['Permission']) !== FALSE)
                        $user->setAvatarUrl(UserTableMap::COL_PERMISSION_STAFF);
                }

                $user = $usersQuery->select(['Id', 'Username', 'Email', 'FirstName', 'LastName', 'AvatarUrl', 'Permission'])->findOneById($user->getId());

                return $this->render($request, $response, "/events/user.html.twig", [
                    'message' => ["Type" => "Success", "Message" => "Modifications were successful!"],
                    'user' => $user,
                ]);
            } else {
                return $this->Unauthorised(false, $request, $response, $args);
            }
        }
    }

    public function GetUsers(Request $request, Response $response, $args) {
        $this->page_title = "All Users";
        $page = $args["page"] ?? 1;
        $users = UserQuery::create()->paginate($page, self::USERS_PER_PAGE);
        return $this->render($request, $response, "/users/users.html.twig", [
            'users' => $users,
        ]);
    }
    
    public function LoginSession($username, $password = null){
        $user = null;
        if(is_a($username, User::class)){
            $user = $username;
        } else if (strlen($username) == 0 || strlen($password) == 0 || $username == null || $password == null) {
            return ["Error" => ["Message" => "Username and password must be given."]];
        } else {
            if(!User::CheckLogin($username, $password)) {
                $this->ci->get("logger")->addDebug(sprintf("[DEBUG][AUTH] Bad details given for (%s) using password (%)", $username, $password));
                return ["Error" => ["Message" => "Incorrect login details."]];
            }

            $user = UserQuery::create()->findOneByEmail($username);
            if($user == null)
                $user = UserQuery::create()->findOneByUsername($username);
            if($user == null){
                $this->ci->get("logger")->addError(sprintf("[ERROR][AUTH] Login was said to be valid, but no user with %s could be found with that username/email."), $username);
                return ["Error" => ["Message" => "A miscellaneous error has occurred and has been reported."]];
            }
        }
        $this->ci->get("logger")->addDebug(sprintf("[DEBUG][AUTH] Logged in user %s", $user->getUsername()));
        $segment = $this->ci->get("session")->getSegment('NorthEastEvents\Login');
        $user_session = $segment->get('user', null);
        if($user_session != null){
            // User was logged in before, invalidate previous session.
            $this->ci->get("session")->clear();
            $this->ci->get("session")->regenerateId();
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

    public function CreateUserGET(Request $request, Response $response, array $args = []){
        return $this->render($request, $response, "/users/register.html.twig", []);
    }

    public function CreateUserPOST(Request $request, Response $response, array $args = []) {
        $username = $request->getParsedBody()['username'] ?? null;
        $password = $request->getParsedBody()['password'] ?? null;
        $email = $request->getParsedBody()['email'] ?? null;
        $fname = $request->getParsedBody()['first_name'] ?? null;
        $lname = $request->getParsedBody()['last_name'] ?? null;
        $avatar =$request->getParsedBody()['avatar'] ?? null;

        $previousDetails = [
            "Username" => $username,
            "Password" => null,
            "Email" => $email,
            "FirstName" => $fname,
            "LastName" => $lname,
            "Avatar" => $avatar
        ];

        // Parse username
        $failure = false;
        if($username == null) {
            $this->ci->get("flash")->addMessageNow('Error', 'You must provide a username.');
            $failure = true;
        }
        if (UserQuery::create()->findOneByUsername($username) != null) {
            $this->ci->get("flash")->addMessageNow('Error', 'Username has been taken');
            $failure = true;
        }

        // Parse email
        if($email == null) {
            $this->ci->get("flash")->addMessageNow('Error', 'You must provide an email.');
            $failure = true;
        }
        if(UserQuery::create()->findOneByEmail($email) != null) {
            $this->ci->get("flash")->addMessageNow('Error', 'Email is in use.');
            $failure = true;
        }

        // Parse password
        if($password == null || strlen($password) < 4) {
            $this->ci->get("flash")->addMessageNow('Error', 'Passwords must be larger than 4 characters.');
            $failure = true;
        }

        if($failure){
            return $this->render($request, $response, "/users/register.html.twig", ["previous_details" => $previousDetails]);
        }


        $user = new User();
        $user->setUsername($username);
        $user->setPassword($password);
        $user->setEmail($email);

        $user->setFirstName($fname);
        $user->setLastName($lname);
        $user->setAvatarUrl($avatar);
        $user->save();

        $this->LoginSession($user);
        $path = $this->ci->get("router")->pathFor("UserCurrentGET");
        return $response->withStatus(302)->withHeader("Location", $path);
    }

    public function APIUserOperations(Request $request, Response $response, $args) {
        $usersQuery = UserQuery::create();
        $user = null;

        /** @var User $currentUser */
        $currentUser = $request->getAttribute("current_user", null);

        if(isset($args['userID']))
            $user = $usersQuery->findOneById($args['userID']);
        else {
            if(!$currentUser == null) {
                $user = $currentUser;
            } else {
                return $response->withJson(["Error" => ["Message" => "You must login to view your own data."]], 403);
            }
        }

        // This user doesn't exist
        if ($user == null) {
            $this->APINotFound(null, $request, $response, $args);
        } else if ($request->isGet()) {
            // JSON example, with sensitive:
            //{"Id":7,"Username":"edavis","Password":"$2y$10$SgwNlcG5kMJt35E34EiHIObrj7BfhXjcWGOFZFUuLtU","Email":"cook.elliot@kelly.com","FirstName":"Ryan","LastName":"Hunter","AvatarUrl":"http:\/\/lorempixel.com\/640\/480\/?51557","Permission":"staff","CreatedAt":"2016-03-19T03:14:08+00:00","UpdatedAt":"2016-03-19T03:14:08+00:00"}
            // Public view:
            //{"Id":7,"Username":"edavis","AvatarUrl":"http:\/\/lorempixel.com\/640\/480\/?51557","Permission":"1"}
            // Same format, which is great! All that is needed is a check if user is logged in && query_user == current_user to return sensitive.

            if($request->getAttribute("authorised", false)) {
                $user = $usersQuery->select(['Id', 'Username', 'Email', 'FirstName', 'LastName', 'AvatarUrl', 'Permission'])->findOneById($user->getId());
            } else {
                $user = $usersQuery->select(['Id', 'Username', 'AvatarUrl', 'Permission'])->findOneById($user->getId());
            }
            return $response = $response->withJson(['User' => $user]);
        } else if ($request->isDelete()) {
            // TODO: Should the API provide this?
            return $response = $response->withJson(['Error' => ["Message" => "Deleting user accounts is not currently permissible using the API."]], 405);
        } else if ($request->isPut() || $request->isPatch()) {
            if($request->getAttribute("authorisation", false)) {
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
                        $user->setAvatarUrl(UserTableMap::COL_PERMISSION_NORMAL);
                    if (stripos('staff', $userjson['Permission']) !== FALSE)
                        $user->setAvatarUrl(UserTableMap::COL_PERMISSION_STAFF);
                }
                return $response->withJson([ "Success" => "The user was successfully modified.", "User" => [ $user->getId(), $user->getUsername(), $user->getAvatarUrl(), $user->getPermission() ]]);
            } else {
                return $this->Unauthorised(false, $request, $response, $args);
            }
        }
    }

    public function APIGetUsers(Request $request, Response $response) {
        $users = UserQuery::create()->select(['Id', 'Username', 'AvatarUrl', 'Permission'])->find();
        return $response->withJson($users->getData());
    }

    public function APILoginController(Request $request, Response $response) {
        $username = isset($request->getParsedBody()['username'])? $request->getParsedBody()['username'] : null;
        $password = isset($request->getParsedBody()['password'])? $request->getParsedBody()['password'] : null;
        return $response->withJson($this->LoginSession($username, $password));
    }

    public function APICreateUser(Request $request, Response $response){
        $username = $request->getParsedBody()['username'] ?? null;
        $password = $request->getParsedBody()['password'] ?? null;
        $email = $request->getParsedBody()['email'] ?? null;
        $fname = $request->getParsedBody()['first_name'] ?? null;
        $lname = $request->getParsedBody()['last_name'] ?? null;
        $avatar =$request->getParsedBody()['avatar'] ?? null;
    }
}
