<?php

include_once('model/User.php');

class AboutController {

    private $user;

    public function __construct() {
        // Check that cookie exists.
        if (isset($_COOKIE['eatable-user-session'])) {
            // Initialize the user object using the session id.
            $this->user = User::getUserBySessionId($_COOKIE['eatable-user-session']);
        }
    }

    public function invoke() {
        include 'view/page/about.php';
    }

}
