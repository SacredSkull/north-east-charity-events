<?php

require __DIR__ . '/../src/config/bootstrap.php';
require __DIR__ . '/../src/routes/users.php';

use \NorthEastEvents\User;
use \NorthEastEvents\Bootstrap;

class UserTest extends PHPUnit_Framework_TestCase {
    public function testStaffPermissions(){
        $user = new User();
        $user->setPermission(\NorthEastEvents\Map\UserTableMap::COL_PERMISSION_STAFF);
        $this->assertTrue($user->isAdmin(), "The isAdmin function states this admin user has normal permissions.");

        $user->setPermission(\NorthEastEvents\Map\UserTableMap::COL_PERMISSION_NORMAL);
        $this->assertFalse($user->isAdmin(), "The isAdmin function states this normal user has admin permissions.");
    }

    public function testUserAPIAnonymousRead(){
        $user = new User();
        $faker = Faker\Factory::create("en_GB");

        $generated["UN"] = $faker->userName;
        $generated["PW"] = $faker->password();
        $generated["EM"] = $faker->email;
        $generated["FN"] = $faker->firstName;
        $generated["LN"] = $faker->lastName;
        $generated["AU"] = $faker->imageUrl(90, 90);
        $generated["PM"] = ($faker->boolean(50)? \NorthEastEvents\Map\UserTableMap::COL_PERMISSION_STAFF :
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

        $url = "http://localhost" . Bootstrap::getSlim()->getContainer()->get('router')->pathFor('User_API_REST', [
            'id' => $user->getId()
        ]);

        $response = \Httpful\Request::get($url)->send();

        echo "\nReturned:\n";
        print_r($response->body);

        // JSON API
        $this->assertEquals($generated["UN"], $response->body->Username, "Wrong username returned");
        $this->assertObjectNotHasAttribute("Password", $response->body, "Password should not be public!");
        $this->assertObjectNotHasAttribute("Email", $response->body, "Email should not be public");
        $this->assertObjectNotHasAttribute("FirstName", $response->body, "First name should not be public");
        $this->assertObjectNotHasAttribute("LastName", $response->body, "Last name should not be public");
        $this->assertEquals($generated["AU"], $response->body->AvatarUrl, "Wrong avatar URL returned");
        // Propel requires some additional magic to both hide sensitive fields and enumerate ENUMs.
        $this->assertEquals($generated["PM"],
            \NorthEastEvents\Map\UserTableMap::getValueSet(\NorthEastEvents\Map\UserTableMap::COL_PERMISSION)
                [(int)$response->body->Permission],
            "Incorrect permission returned"
        );

        // Check that the User can log in (after regenerating)
        // Had issue with database column not being long enough to contain the hash!
        $this->assertTrue(User::CheckLogin($generated["UN"], $generated["PW"]), "Login details FAILED!");

        $user->delete();
    }

    // Should be allowed
    public function testUserAPIAnonymousRegistration(){

    }

    // Should fail
    public function testUserAPIAnonymousDelete(){

    }

    // Should fail
    public function userAPIAnonymousModify(){

    }
}