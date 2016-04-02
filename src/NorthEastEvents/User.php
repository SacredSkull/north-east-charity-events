<?php

namespace NorthEastEvents;

use NorthEastEvents\Base\User as BaseUser;
use NorthEastEvents\Base\UserQuery;

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
class User extends BaseUser
{
    public function isAdmin(){
        return Map\UserTableMap::COL_PERMISSION_STAFF == $this->getPermission();
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
