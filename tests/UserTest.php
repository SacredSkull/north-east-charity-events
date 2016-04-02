<?php

require __DIR__ . '/../src/config/bootstrap.php';
require __DIR__ . '/../src/routes/Controller.php';
require __DIR__ . '/../src/routes/UserController.php';

use \NorthEastEvents\User;
use \NorthEastEvents\Bootstrap;
use \GuzzleHttp\Client;

class UserTest extends PHPUnit_Framework_TestCase {
    private $client;
    private $faker;

    public function setUp(){
        $this->client = new Client([
            'base_uri' => 'http://localhost',
            'http_errors' => false
        ]);
        $this->faker = Faker\Factory::create("en_GB");
    }

    public function tearDown() {
        $this->client = null;
        $this->faker = null;
    }

    public function testStaffPermissions(){
        $user = new User();
        $user->setPermission(\NorthEastEvents\Map\UserTableMap::COL_PERMISSION_STAFF);
        $this->assertTrue($user->isAdmin(), "The isAdmin function states this admin user has normal permissions.");

        $user->setPermission(\NorthEastEvents\Map\UserTableMap::COL_PERMISSION_NORMAL);
        $this->assertFalse($user->isAdmin(), "The isAdmin function states this normal user has admin permissions.");
    }

    public function testUserAPILogin(){
        $generated["UN"] = $this->faker->userName;
        $generated["PW"] = $this->faker->password();
        $generated["EM"] = $this->faker->email;
        $generated["FN"] = $this->faker->firstName;
        $generated["LN"] = $this->faker->lastName;
        $generated["AU"] = $this->faker->imageUrl(90, 90);
        $generated["PM"] = ($this->faker->boolean(50)? \NorthEastEvents\Map\UserTableMap::COL_PERMISSION_STAFF :
            \NorthEastEvents\Map\UserTableMap::COL_PERMISSION_NORMAL);

        $user = new User();
        $user->setUsername($generated["UN"]);
        $user->setPassword($generated["PW"]);
        $user->setEmail($generated["EM"]);

        $user->save();

        try {

            $response = $this->client->post(Bootstrap::getSlim()->getContainer()->get('router')->pathFor('APIUserLogin'), [
                'form_params' => [
                    'username' => $generated["UN"],
                    "password" => $generated["PW"],
                ]
            ]);

            $json = json_decode($response->getBody());
            print_r($json);
            $this->assertObjectHasAttribute("Session", $json, "Session id was not returned for a valid login.");
            $this->assertObjectNotHasAttribute("Error", $json, "Valid details returned login error");
        } catch(Exception $e){

        } finally {
            $user->delete();
        }
    }

    public function testUserAPIAuthorisedRead(){
        $generated["UN"] = $this->faker->userName;
        $generated["PW"] = $this->faker->password();
        $generated["EM"] = $this->faker->email;
        $generated["FN"] = $this->faker->firstName;
        $generated["LN"] = $this->faker->lastName;
        $generated["AU"] = $this->faker->imageUrl(90, 90);
        $generated["PM"] = \NorthEastEvents\Map\UserTableMap::COL_PERMISSION_NORMAL;

        $jar = new \GuzzleHttp\Cookie\CookieJar;
        $response = $this->client->post(Bootstrap::getSlim()->getContainer()->get('router')->pathFor('APIUserCreate'), [
            'form_params' => [
                'username' => $generated["UN"],
                "password" => $generated["PW"],
                "email" => $generated["EM"],
                "first_name" => $generated["FN"],
                "last_name" => $generated["LN"],
                "avatar" => $generated["AU"],
            ]
        ], [
            'cookies' => $jar
        ]);

        $json = json_decode($response->getBody());
        echo "Registering returned:\n";
        print_r($json);

        $id = $json->User->Id;

        $response = $this->client->get(Bootstrap::getSlim()->getContainer()->get('router')->pathFor('APIUserGET'), [
            'cookies' => $jar
        ]);

        $json = json_decode($response->getBody());
        //TODO: session is not transferring to second GET request...

        echo "Getting own data returned:\n";
        print_r($json);
        $response = json_decode((string)$response->getBody());

        $this->assertEquals($json->Username, $generated["UN"], "Wrong username");
        $this->assertEquals($json->Email, $generated["EM"], "Wrong email");
        $this->assertEquals($json->FirstName, $generated["FN"], "Wrong first name");
        $this->assertEquals($json->LastName, $generated["LN"], "Wrong last name");
        $this->assertEquals($json->AvatarUrl, $generated["AU"], "Wrong avatar");
        $this->assertEquals($generated["PM"],
            \NorthEastEvents\Map\UserTableMap::getValueSet(\NorthEastEvents\Map\UserTableMap::COL_PERMISSION)
            [(int)$json->Permission],
            "Incorrect permission returned"
        );

        \NorthEastEvents\UserQuery::create()->findOneById($id)->delete();
    }

    public function testUserAPIAnonymousRead(){
        $user = new User();

        $generated["UN"] = $this->faker->userName;
        $generated["PW"] = $this->faker->password();
        $generated["EM"] = $this->faker->email;
        $generated["FN"] = $this->faker->firstName;
        $generated["LN"] = $this->faker->lastName;
        $generated["AU"] = $this->faker->imageUrl(90, 90);
        $generated["PM"] = ($this->faker->boolean(50)? \NorthEastEvents\Map\UserTableMap::COL_PERMISSION_STAFF :
            \NorthEastEvents\Map\UserTableMap::COL_PERMISSION_NORMAL);

        $user->setUsername($generated["UN"]);
        $user->setPassword($generated["PW"]);
        $user->setEmail($generated["EM"]);
        $user->setFirstName($generated["FN"]);
        $user->setLastName($generated["LN"]);
        $user->setAvatarUrl($generated["AU"]);
        $user->setPermission($generated["PM"]);

        echo "Generated:\n";
        print_r($generated);

        $user->save();

        $response = $this->client->get(Bootstrap::getSlim()->getContainer()->get('router')->pathFor('APIUserOperations', [
            'id' => $user->getId()
        ]));

        echo "\nReturned:\n";
        $response = json_decode((string)$response->getBody())->User;
        print_r($response);

        // JSON API
        $this->assertEquals($generated["UN"], $response->Username, "Wrong username returned");
        $this->assertObjectNotHasAttribute("Password", $response, "Password should not be public!");
        $this->assertObjectNotHasAttribute("Email", $response, "Email should not be public");
        $this->assertObjectNotHasAttribute("FirstName", $response, "First name should not be public");
        $this->assertObjectNotHasAttribute("LastName", $response, "Last name should not be public");
        $this->assertEquals($generated["AU"], $response->AvatarUrl, "Wrong avatar URL returned");

        // Propel requires some additional magic to both hide sensitive fields and enumerate ENUMs.
        $this->assertEquals($generated["PM"],
            \NorthEastEvents\Map\UserTableMap::getValueSet(\NorthEastEvents\Map\UserTableMap::COL_PERMISSION)
                [(int)$response->Permission],
            "Incorrect permission returned"
        );

        // Check that the User can log in (after regenerating)
        // Had issue with database column not being long enough to contain the hash!
        $this->assertTrue(User::CheckLogin($generated["UN"], $generated["PW"]), "Login details FAILED!");
        $user->delete();
    }

    // Should be allowed
    public function testUserAPIAnonymousRegistration(){
        $generated["UN"] = $this->faker->userName;
        $generated["PW"] = $this->faker->password();
        $generated["EM"] = $this->faker->email;
        $generated["FN"] = $this->faker->firstName;
        $generated["LN"] = $this->faker->lastName;
        $generated["AU"] = $this->faker->imageUrl(90, 90);
        $generated["PM"] = \NorthEastEvents\Map\UserTableMap::COL_PERMISSION_NORMAL;

        echo "Generated:\n";
        print_r([
            'form_params' => [
                'username' => $generated["UN"],
                "password" => $generated["PW"],
                "email" => $generated["EM"],
                "first_name" => $generated["FN"],
                "last_name" => $generated["LN"],
                "avatar" => $generated["AU"],
            ]
        ]);

        $response = $this->client->post(Bootstrap::getSlim()->getContainer()->get('router')->pathFor('APIUserCreate'), [
            'form_params' => [
                'username' => $generated["UN"],
                "password" => $generated["PW"],
                "email" => $generated["EM"],
                "first_name" => $generated["FN"],
                "last_name" => $generated["LN"],
                "avatar" => $generated["AU"],
            ]
        ]);

        $this->assertTrue(($response->getStatusCode() == 201 || $response->getStatusCode() == 200), sprintf("Expected status 201, Created (or 200) - got %s, %s", $response->getStatusCode(), $response->getReasonPhrase()));
        $json = json_decode((string)$response->getBody());

        echo "Received:\n";
        print_r($json);

        $this->assertEquals($json->User->Username, $generated["UN"], "Wrong username");
        $this->assertEquals($json->User->Email, $generated["EM"], "Wrong email");
        $this->assertEquals($json->User->FirstName, $generated["FN"], "Wrong first name");
        $this->assertEquals($json->User->LastName, $generated["LN"], "Wrong last name");
        $this->assertEquals($json->User->AvatarUrl, $generated["AU"], "Wrong avatar");
        $this->assertEquals($generated["PM"],
            \NorthEastEvents\Map\UserTableMap::getValueSet(\NorthEastEvents\Map\UserTableMap::COL_PERMISSION)
            [(int)$json->User->Permission],
            "Incorrect permission returned"
        );
    }

    // Should fail
    public function testUserAPIAnonymousDelete(){
        $response = $this->client->delete(Bootstrap::getSlim()->getContainer()->get('router')->pathFor('APIUserOperations', [
            'id' => \NorthEastEvents\UserQuery::create()->findOne()->getId()
        ]));
        $this->assertTrue($response->getStatusCode() == 405);
    }

    // Should fail
    public function userAPIAnonymousModify(){
        $response = $this->client->patch(Bootstrap::getSlim()->getContainer()->get('router')->pathFor('APIUserOperations', [
            'id' => \NorthEastEvents\UserQuery::create()->findOne()->getId()
        ]));
    }
}