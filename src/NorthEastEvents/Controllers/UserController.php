<?php
namespace NorthEastEvents\Controllers;

use Interop\Container\ContainerInterface;
use NorthEastEvents\Middleware\AuthorisedRouteMiddleware;
use NorthEastEvents\Middleware\BasicAuthMiddleware;
use NorthEastEvents\Models\Base\EventUsers;
use NorthEastEvents\Models\Event;
use NorthEastEvents\Models\EventUsersQuery;
use NorthEastEvents\Models\Map\UserTableMap;
use NorthEastEvents\Models\User;
use NorthEastEvents\Models\UserQuery;
use Propel\Runtime\Formatter\ObjectFormatter;
use Propel\Runtime\Propel;
use Propel\Runtime\Util\PropelModelPager;
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

    public function UserOperations(Request $req, Response $res, $args){
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
                return $this->Unauthorised("You must login to view your own data.", $req, $res, $args);
            }
        }

        // This user doesn't exist
        if ($user == null) {
            $this->NotFound(null, $req, $res, $args);
        } else if ($req->isGet()) {
            if(User::CheckAuthorised($currentUser, $user)) {
                $user = $usersQuery->select(['Id', 'Username', 'Email', 'Bio', 'City', 'FirstName', 'LastName', 'AvatarUrl', 'Permission'])->findOneById($user->getId());

                $con = Propel::getWriteConnection(\NorthEastEvents\Models\Map\EventTableMap::DATABASE_NAME);
                $sql = "SELECT realEvents.* FROM (SELECT eventID FROM event_users WHERE userID = :user_id) as eventUsers LEFT JOIN event AS realEvents ON eventUsers.eventID = realEvents.id;";

                $stmt = $con->prepare($sql);
                $stmt->execute([":user_id" => $user["Id"]]);

                $formatter = new ObjectFormatter();
                $formatter->setClass(Event::class);
                $userEvents = $formatter->format($con->getDataFetcher($stmt));

                return $this->render($req, $res, "/users/user.html.twig", [
                    'user' => $user,
                    "user_events" => $userEvents
                ]);
            }

            $user = $usersQuery->select(['Id', 'Username', 'AvatarUrl', 'Bio', 'City', 'Permission'])->findOneById($user->getId());
            
            $con = Propel::getWriteConnection(\NorthEastEvents\Models\Map\EventTableMap::DATABASE_NAME);
            $sql = "SELECT realEvents.* FROM (SELECT eventID FROM event_users WHERE userID = :user_id AND private = false) as eventUsers "
                   . "LEFT JOIN event AS realEvents ON eventUsers.eventID = realEvents.id;";

            $stmt = $con->prepare($sql);
            $stmt->execute([":user_id" => $user['Id']]);

            $formatter = new ObjectFormatter();
            $formatter->setClass(Event::class);
            $userEvents = $formatter->format($con->getDataFetcher($stmt));

            return $this->render($req, $res, "/users/user.html.twig", [
                'user' => $user,
                "user_events" => $userEvents
            ]);
        } else if ($req->isDelete()) {
            // TODO: Should the API provide this?
            return $this->NotAllowed(null, $req, $res, $args);
        } else if ($req->isPut() || $req->isPatch()) {
            if($currentUser->isAuthorised($user)) {
                $userjson = $req->getParsedBody()['user'];
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
                $user = $usersQuery->select(['Id', 'Username', 'Email', 'Bio', 'City', 'FirstName', 'LastName', 'AvatarUrl', 'Permission'])->findOneById($user->getId());

                return $this->render($req, $res, "/users/user.html.twig", [
                    'message' => ["Type" => "Success", "Message" => "Modifications were successful!"],
                    'user' => $user,
                ]);
            } else {
                return $this->Unauthorised(false, $req, $res, $args);
            }
        }
    }

    public function GetUsers(Request $req, Response $res, $args) {
        $this->page_title = "All Users";
        $page = $args["page"] ?? 1;
        $users = UserQuery::create()->paginate($page, self::USERS_PER_PAGE);
        return $this->render($req, $res, "/users/users.html.twig", [
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
            $segment->clear();
            $this->ci->get("session")->regenerateId();
        }
        $segment->set('user', $user);
        return ["Session" => session_id()];
    }

    public function LoginController(Request $req, Response $res) {
        $username = $req->getParsedBody()['username'] ?? null;
        $password = $req->getParsedBody()['password'] ?? null;
        $result = $this->LoginSession($username, $password);
        if($result["Error"] ?? null){
            $this->ci->get("flash")->addMessage("Error", $result["Error"]["Message"]);
            return $res->withHeader("Location", $this->ci->get("router")->pathFor("Home"));
        }
        return $res->withHeader("Location", $this->ci->get("router")->pathFor("UserCurrentGET"));
    }

    public function LogoutController(Request $req, Response $res) {
        $segment = $this->ci->get("session")->getSegment('NorthEastEvents\Login');
        if($segment->get('user', null)){
            $segment->clear();
        } else {
            $this->ci->get("flash")->addMessage("Warning", "Not logged in|Logging out is a privilege enjoyed only by those logged in.");
        }
        return $res->withHeader("Location", $this->ci->get("router")->pathFor("Home"));
    }

    public function CreateUserGET(Request $req, Response $res, array $args = []){
        return $this->render($req, $res, "/users/register.html.twig", []);
    }

    public function CreateUserPOST(Request $req, Response $res, array $args = []) {
        $username = $req->getParsedBody()['username'] ?? null;
        $password = $req->getParsedBody()['password'] ?? null;
        $email = $req->getParsedBody()['email'] ?? null;
        $fname = $req->getParsedBody()['first_name'] ?? null;
        $lname = $req->getParsedBody()['last_name'] ?? null;
        $avatar = $req->getParsedBody()['avatar'] ?? null;
        $bio = $req->getParsedBody()['bio'] ?? null;
        $city = $req->getParsedBody()['city'] ?? null;

        $previousDetails = [
            "Username" => $username,
            "Password" => null,
            "Email" => $email,
            "FirstName" => $fname,
            "LastName" => $lname,
            "Avatar" => $avatar,
            "Bio" => $bio,
            "City" => $city
        ];

        // Parse username
        $failure = false;
        if($username == null) {
            $this->ci->get("flash")->addMessageNow('Error', 'You must provide a username.|<br>');
            $failure = true;
        }
        if (UserQuery::create()->findOneByUsername($username) != null) {
            $this->ci->get("flash")->addMessageNow('Error', 'Username has been taken|<br>');
            $failure = true;
        }

        // Parse email
        if($email == null) {
            $this->ci->get("flash")->addMessageNow('Error', 'You must provide an email.|<br>');
            $failure = true;
        }
        if(UserQuery::create()->findOneByEmail($email) != null) {
            $this->ci->get("flash")->addMessageNow('Error', 'Email is in use.|<br>');
            $failure = true;
        }

        // Parse password
        if($password == null || strlen($password) < 4) {
            $this->ci->get("flash")->addMessageNow('Error', 'Passwords must be larger than 4 characters.|<br>');
            $failure = true;
        }

        if($failure){
            return $this->render($req, $res, "/users/register.html.twig", ["previous_details" => $previousDetails]);
        }

        $user = new User();
        $user->setUsername($username);
        $user->setPassword($password);
        $user->setEmail($email);

        $user->setFirstName($fname);
        $user->setLastName($lname);
        $user->setAvatarUrl($avatar);
        $user->setCity($city);
        $user->setBio($bio);
        $user->save();

        $this->LoginSession($user);
        $path = $this->ci->get("router")->pathFor("UserCurrentGET");
        return $res->withStatus(302)->withHeader("Location", $path);
    }
    
    public function UserEventsGET(Request $req, Response $res, $args){
        $user = UserQuery::create()->findOneById($args["userID"] ?? null);
        if($user == null){
            $this->NotFound(null, $req, $res, $args);
        }
    }

    public function APIUserOperations(Request $req, Response $res, $args) {
        $usersQuery = UserQuery::create();
        $user = null;

        /** @var User $currentUser */
        $currentUser = $req->getAttribute("current_user", null);

        if(isset($args['userID']))
            $user = $usersQuery->findOneById($args['userID']);
        else {
            if(!$currentUser == null) {
                $user = $currentUser;
            } else {
                return $res->withJson(["Error" => ["Message" => "You must login to view your own data."]], 403);
            }
        }

        // This user doesn't exist
        if ($user == null) {
            $this->APINotFound(null, $req, $res, $args);
        } else if ($req->isGet()) {
            // JSON example, with sensitive:
            //{"Id":7,"Username":"edavis","Password":"$2y$10$SgwNlcG5kMJt35E34EiHIObrj7BfhXjcWGOFZFUuLtU","Email":"cook.elliot@kelly.com","FirstName":"Ryan","LastName":"Hunter","AvatarUrl":"http:\/\/lorempixel.com\/640\/480\/?51557","Permission":"staff","CreatedAt":"2016-03-19T03:14:08+00:00","UpdatedAt":"2016-03-19T03:14:08+00:00"}
            // Public view:
            //{"Id":7,"Username":"edavis","AvatarUrl":"http:\/\/lorempixel.com\/640\/480\/?51557","Permission":"1"}
            // Same format, which is great! All that is needed is a check if user is logged in && query_user == current_user to return sensitive.

            if($req->getAttribute("authorised", false)) {
                $user = $usersQuery->select(['Id', 'Username', 'Email', 'FirstName', 'LastName', 'AvatarUrl', 'Permission'])->findOneById($user->getId());
            } else {
                $user = $usersQuery->select(['Id', 'Username', 'AvatarUrl', 'Permission'])->findOneById($user->getId());
            }
            return $res = $res->withJson(['User' => $user]);
        } else if ($req->isDelete()) {
            // TODO: Should the API provide this?
            return $res = $res->withJson(['Error' => ["Message" => "Deleting user accounts is not currently permissible using the API."]], 405);
        } else if ($req->isPut() || $req->isPatch()) {
            if($req->getAttribute("authorisation", false)) {
                $userjson = $req->getParsedBody()['user'];
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
                return $res->withJson([ "Success" => "The user was successfully modified.", "User" => [ $user->getId(), $user->getUsername(), $user->getAvatarUrl(), $user->getPermission() ]]);
            } else {
                return $this->Unauthorised(false, $req, $res, $args);
            }
        }
    }

    public function APIGetUsers(Request $req, Response $res) {
        $users = UserQuery::create()->select(['Id', 'Username', 'AvatarUrl', 'Permission'])->find();
        return $res->withJson($users->getData());
    }

    public function APILoginController(Request $req, Response $res) {
        $username = $req->getParsedBody()['username'] ?? null;
        $password = $req->getParsedBody()['password'] ?? null;
        return $res->withJson($this->LoginSession($username, $password));
    }

    public function APICreateUser(Request $req, Response $res){
        $username = $req->getParsedBody()['username'] ?? null;
        $password = $req->getParsedBody()['password'] ?? null;
        $email = $req->getParsedBody()['email'] ?? null;
        $fname = $req->getParsedBody()['first_name'] ?? null;
        $lname = $req->getParsedBody()['last_name'] ?? null;
        $avatar =$req->getParsedBody()['avatar'] ?? null;
    }
}
