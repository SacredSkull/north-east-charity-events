<?php

use NorthEastEvents\Middleware\AuthorisedRouteMiddleware;
use NorthEastEvents\Middleware\BasicAuthMiddleware;
use \NorthEastEvents\Models\Map;
use \NorthEastEvents\Models\User;
use \NorthEastEvents\Controllers\Routes;
use \Psr\Http\Message\ServerRequestInterface as RequestInterface;
use \Psr\Http\Message\ResponseInterface as ResponseInterface;
use Slim\Http\Environment;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\RequestBody;
use Slim\Http\UploadedFile;
use Slim\Http\Uri;

class UserTest extends PHPUnit_Framework_TestCase {
    private $app = null;
    const BASE_URI = "http://docker.dev";
    private $faker;

    public function setUp(){
        $this->faker = Faker\Factory::create("en_GB");
        $bootstrap = new \NorthEastEvents\Bootstrap();
        $this->app = $bootstrap->initialise();
    }

    public function tearDown() {
        $this->app = null;
        $this->faker = null;
    }

    public static function addBasicAuth(string $username, string $password, RequestInterface $request){
        $basicauth = base64_encode($username . ":" . $password);
        return $request->withHeader("Authorization", "Basic " . $basicauth);
    }

    public function fakeRequest($uri = self::BASE_URI, $body = null, $cookies = []){
        $env = Environment::mock();
        $uri = Uri::createFromString($uri);

        $headers = Headers::createFromEnvironment($env);
        $serverParams = $env->all();

        if($body == null)
            $body = new RequestBody();

        $uploadedFiles = UploadedFile::createFromEnvironment($env);

        $req = new Request('GET', $uri, $headers, $cookies, $serverParams, $body, $uploadedFiles);
        return $req;
    }

    public function testStaffPermissions(){
        $user = new User();
        $user->setPermission(Map\UserTableMap::COL_PERMISSION_STAFF);
        $this->assertTrue($user->isAdmin(), "The isAdmin function states this admin user has normal permissions.");

        $user->setPermission(Map\UserTableMap::COL_PERMISSION_NORMAL);
        $this->assertFalse($user->isAdmin(), "The isAdmin function states this normal user has admin permissions.");
    }

    public function testUserAPILogin(){
        $generated["UN"] = $this->faker->userName;
        $generated["PW"] = $this->faker->password();
        $generated["EM"] = $this->faker->email;
        $generated["FN"] = $this->faker->firstName;
        $generated["LN"] = $this->faker->lastName;
        $generated["AU"] = $this->faker->imageUrl(90, 90);
        $generated["PM"] = ($this->faker->boolean(50)? Map\UserTableMap::COL_PERMISSION_STAFF :
            Map\UserTableMap::COL_PERMISSION_NORMAL);

        $user = new User();
        $user->setUsername($generated["UN"]);
        $user->setPassword($generated["PW"]);
        $user->setEmail($generated["EM"]);

        $user->save();

        printf("\nusername: %s, password: %s\n", $user->getUsername(), $generated["PW"]);

        $path = "/api/user";

        /** @var RequestInterface $req */
        $req = $this->fakeRequest(self::BASE_URI . $path);
        $req = $req->withUri($req->getUri()->withUserInfo($generated["UN"], $generated["PW"]));

        /** @var \Slim\App $app */
        $app = $this->app;

        $app->get($path, function(RequestInterface $req, ResponseInterface $res, array $args) use ($app) {
            $uc = new \NorthEastEvents\Controllers\UserController($app->getContainer());
            return $uc->APIUserOperations($req, $res, $args);
        })->add(new AuthorisedRouteMiddleware())->add(new BasicAuthMiddleware());

        $res = $app($req, new \Slim\Http\Response());
        $res->getBody()->rewind();

        $json = json_decode($res->getBody()->getContents());
        print_r($json);
        $this->assertObjectNotHasAttribute("Error", $json, "Valid details returned login error");
        $this->assertObjectHasAttribute("FirstName", $json->User, "Private first name was not returned for the current user.");
        $this->assertObjectHasAttribute("LastName", $json->User, "Private last name was not returned for the current user.");

        $user->delete();
    }

    public function testUsernameSpecialCharacters(){
        // TODO: make sure usernames with special chars fail.
    }
    
//
//    public function testUserAPIAnonymousRead(){
//        $user = new User();
//
//        $generated["UN"] = $this->faker->userName;
//        $generated["PW"] = $this->faker->password();
//        $generated["EM"] = $this->faker->email;
//        $generated["FN"] = $this->faker->firstName;
//        $generated["LN"] = $this->faker->lastName;
//        $generated["AU"] = $this->faker->imageUrl(90, 90);
//        $generated["PM"] = ($this->faker->boolean(50)? Map\UserTableMap::COL_PERMISSION_STAFF :
//            Map\UserTableMap::COL_PERMISSION_NORMAL);
//
//        $user->setUsername($generated["UN"]);
//        $user->setPassword($generated["PW"]);
//        $user->setEmail($generated["EM"]);
//        $user->setFirstName($generated["FN"]);
//        $user->setLastName($generated["LN"]);
//        $user->setAvatarUrl($generated["AU"]);
//        $user->setPermission($generated["PM"]);
//
//        echo "Generated:\n";
//        print_r($generated);
//
//        $user->save();
//
//        $response = $this->client->get(Bootstrap::getSlim()->getContainer()->get('router')->pathFor('APIUserOperations', [
//            'id' => $user->getId()
//        ]));
//
//        echo "\nReturned:\n";
//        $response = json_decode((string)$response->getBody())->User;
//        print_r($response);
//
//        // JSON API
//        $this->assertEquals($generated["UN"], $response->Username, "Wrong username returned");
//        $this->assertObjectNotHasAttribute("Password", $response, "Password should not be public!");
//        $this->assertObjectNotHasAttribute("Email", $response, "Email should not be public");
//        $this->assertObjectNotHasAttribute("FirstName", $response, "First name should not be public");
//        $this->assertObjectNotHasAttribute("LastName", $response, "Last name should not be public");
//        $this->assertEquals($generated["AU"], $response->AvatarUrl, "Wrong avatar URL returned");
//
//        // Propel requires some additional magic to both hide sensitive fields and enumerate ENUMs.
//        $this->assertEquals($generated["PM"],
//            Map\UserTableMap::getValueSet(Map\UserTableMap::COL_PERMISSION)
//                [(int)$response->Permission],
//            "Incorrect permission returned"
//        );
//
//        // Check that the User can log in (after regenerating)
//        // Had issue with database column not being long enough to contain the hash!
//        $this->assertTrue(User::CheckLogin($generated["UN"], $generated["PW"]), "Login details FAILED!");
//        $user->delete();
//    }
//
//    // Should be allowed
//    public function testUserAPIAnonymousRegistration(){
//        $generated["UN"] = $this->faker->userName;
//        $generated["PW"] = $this->faker->password();
//        $generated["EM"] = $this->faker->email;
//        $generated["FN"] = $this->faker->firstName;
//        $generated["LN"] = $this->faker->lastName;
//        $generated["AU"] = $this->faker->imageUrl(90, 90);
//        $generated["PM"] = Map\UserTableMap::COL_PERMISSION_NORMAL;
//
//        echo "Generated:\n";
//        print_r([
//            'form_params' => [
//                'username' => $generated["UN"],
//                "password" => $generated["PW"],
//                "email" => $generated["EM"],
//                "first_name" => $generated["FN"],
//                "last_name" => $generated["LN"],
//                "avatar" => $generated["AU"],
//            ]
//        ]);
//
//        $response = $this->client->post(Bootstrap::getSlim()->getContainer()->get('router')->pathFor('APIUserCreate'), [
//            'form_params' => [
//                'username' => $generated["UN"],
//                "password" => $generated["PW"],
//                "email" => $generated["EM"],
//                "first_name" => $generated["FN"],
//                "last_name" => $generated["LN"],
//                "avatar" => $generated["AU"],
//            ]
//        ]);
//
//        $this->assertTrue(($response->getStatusCode() == 201 || $response->getStatusCode() == 200), sprintf("Expected status 201, Created (or 200) - got %s, %s", $response->getStatusCode(), $response->getReasonPhrase()));
//        $json = json_decode((string)$response->getBody());
//
//        echo "Received:\n";
//        print_r($json);
//
//        $this->assertEquals($json->User->Username, $generated["UN"], "Wrong username");
//        $this->assertEquals($json->User->Email, $generated["EM"], "Wrong email");
//        $this->assertEquals($json->User->FirstName, $generated["FN"], "Wrong first name");
//        $this->assertEquals($json->User->LastName, $generated["LN"], "Wrong last name");
//        $this->assertEquals($json->User->AvatarUrl, $generated["AU"], "Wrong avatar");
//        $this->assertEquals($generated["PM"],
//            Map\UserTableMap::getValueSet(Map\UserTableMap::COL_PERMISSION)
//            [(int)$json->User->Permission],
//            "Incorrect permission returned"
//        );
//    }
//
//    // Should fail
//    public function testUserAPIAnonymousDelete(){
//        $response = $this->client->delete(Bootstrap::getSlim()->getContainer()->get('router')->pathFor('APIUserOperations', [
//            'id' => \NorthEastEvents\UserQuery::create()->findOne()->getId()
//        ]));
//        $this->assertTrue($response->getStatusCode() == 405);
//    }
//
//    // Should fail
//    public function userAPIAnonymousModify(){
//        $response = $this->client->patch(Bootstrap::getSlim()->getContainer()->get('router')->pathFor('APIUserOperations', [
//            'id' => \NorthEastEvents\UserQuery::create()->findOne()->getId()
//        ]));
//    }
}