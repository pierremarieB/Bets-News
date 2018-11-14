<?php

namespace BetCompare\Framework\Authentication;

use BetCompare\Framework\Request;

class AuthenticationManager {
    private $users;
    private $request;
    private $auth;
    private $isConnected;

    public function __construct(Request $request) {
        $this->request = $request;
        $this->auth = array();
    }

    public function checkAuth($login, $pwd) {
        $this->auth["user"]["login"] = $login;
        $this->request->synchronizeSession($this->auth);
        return;
    }

    public function disconnect() {
        unset($_SESSION["user"]);
    }

    public function isConnected() {
        return !empty($_SESSION["user"]["login"]);
    }

    public function addUser($login, $pwd) {
        return;
    }
}
