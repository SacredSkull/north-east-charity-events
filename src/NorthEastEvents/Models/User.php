<?php

namespace NorthEastEvents\Models;

use NorthEastEvents\Bootstrap;
use NorthEastEvents\Models\Base\User as BaseUser;

/**
 * Skeleton subclass for representing a row from the 'user' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class User extends BaseUser {
    public static function CheckAuthorised($currentUser = null, $targetUser = null) {
        if($currentUser == null) {
            return false;
        }
        if(is_int($currentUser)) {
            $currentUser = UserQuery::create()->findOneById($currentUser);
        }
        if ($currentUser->isAdmin()){
            return true;
        }

        if ($targetUser != null) {
            if (is_a($targetUser, 'User')) {
                // Assume this is a User object
                if ($currentUser->getId() === $targetUser->getId()){
                    return true;
                }
            } else if(is_numeric($targetUser)){
                // User ID
                if ($currentUser->getId() == $targetUser){
                    return true;
                }
            }
        }
        return false;
    }

    // Login is whatever the anonymous client provided: username or email
    public static function CheckLogin($username, $password){
        $user = UserQuery::create()->findOneByUsername($username);
        if($username == null || strlen($username) == 0 || $password == null || strlen($password) == 0)
            return false;
        if($user == null){
            // Let's check their email next
            $user = UserQuery::create()->findOneByEmail($username);
            if($user == null) {
                Bootstrap::getLogger()->addDebug(sprintf("[DEBUG][AUTH] Check Login - Username/email does not exist: %s", $username));
                return false;
            }
        }

        // Easy part is over; now the password!
        // It's better for security to not reveal if one part of the login was wrong (e.g. good email, bad password)
        Bootstrap::getLogger()->addDebug(sprintf("[DEBUG][AUTH] Check Login - sent hash %s; stored hash: %s", password_hash($password, CRYPT_BLOWFISH), $user->getPassword()));
        return password_verify($password, $user->getPassword());
    }

    public function isAdmin() {
        return Map\UserTableMap::COL_PERMISSION_STAFF == $this->getPermission();
    }

    public function isAuthorised($targetUser){
        // This is a shorthand/instance check of this user, rather than a static one.
        return User::CheckAuthorised($this, $targetUser);
    }

    public function preSave(\Propel\Runtime\Connection\ConnectionInterface $con = null) {
        if(UserQuery::create()->findOneByUsername($this->getUsername()) != null)
            return false;
        if(UserQuery::create()->findOneByEmail($this->getEmail()) != null)
            return false;

        $hashed = password_hash($this->getPassword(), CRYPT_BLOWFISH);

        // In case something is wrong with the hash
        if(strlen($hashed) == 0 && password_verify($this->getPassword(), $hashed)){
            Bootstrap::getLogger()->addCritical(sprintf('Hashing has completely failed for user ID: %i, username: %s',
                $this->getId(), $this->getUsername()));
            return false;
        }

        $this->setPassword($hashed);
        return true;
    }
}
