<?php

namespace NorthEastEvents\Controllers;

use NorthEastEvents\Models\UserQuery;

class LoginSession {

    private $current_user;

    public function __construct($user) {
        $this->current_user = $user;
    }

    public function user(){
        return UserQuery::create()->findOneById($this->current_user);
    }
}
