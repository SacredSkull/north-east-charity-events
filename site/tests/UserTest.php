<?php


use \NorthEastEvents\User;

require 'vendor/autoload.php';

class UserTest extends PHPUnit_Extensions_PhptTestCase {
    public function testStaffPermissions()
    {
        $user = new User();
        $user->setPermission("Staff");
        assertTrue(User::isAdmin($user));
    }

    public function testUserPermissions()
    {
        $user = new User();
        $user->setPermission("User");
        assertFalse(User::isAdmin($user));
    }

}