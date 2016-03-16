<?php

require_once __DIR__ . '/../src/config/bootstrap.php';
require __DIR__ . '/../src/routes/users.php';
use \NorthEastEvents\User;

class UserTest extends PHPUnit_Framework_TestCase {
    public function testStaffPermissions(){
        $user = new User();
        $user->setPermission(\NorthEastEvents\Map\UserTableMap::COL_PERMISSION_STAFF);
        $this->assertTrue(User::isAdmin($user));

        $user->setPermission(\NorthEastEvents\Map\UserTableMap::COL_PERMISSION_NORMAL);
        $this->assertFalse(User::isAdmin($user));
    }

    public function userAPIRead(){
        $user = new User();
        $faker = Faker\Factory::create("en_GB");

        $generated["UN"] = $faker->userName;
        $generated["PW"] = $faker->password();
        $generated["EM"] = $faker->email;
        $generated["FN"] = $faker->firstName;
        $generated["LN"] = $faker->lastName;
        $generated["AU"] = $faker->imageUrl();
        $generated["PV"] = ($faker->boolean(30)? \NorthEastEvents\Map\UserTableMap::COL_PERMISSION_STAFF : \NorthEastEvents\Map\UserTableMap::COL_PERMISSION_NORMAL);

        $user->setUsername($generated["UN"]);
        $user->setPassword($generated["PW"]);
        $user->setEmail($generated["EM"]);
        $user->setFirstName($generated["FN"]);
        $user->setLastName($generated["LN"]);
        $user->setAvatarUrl($generated["AU"]);
        $user->setPermission($generated["PV"]);

        $user->save();

        $url = \NorthEastEvents\Bootstrap::getSlim()->getContainer()->get('router')->pathFor('User_API_REST', [
            'id' => $user->getId()
        ]);

        $response =  \Httpful\Request::get($url)->send();
        assertEquals($generated["UN"], $response->body->User);
    }
}