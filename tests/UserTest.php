<?php

require '../src/vendor/autoload.php';
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
        $faker = Faker\Factory::create();
        // TODO: finish this test
    }
}