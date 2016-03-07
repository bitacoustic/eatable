<?php

include_once 'model/User.php';

/*
 * Landing controller.
 */

class LandingController {

    private $user;

    public function __construct() {
        // Check that cookie exists.
        if (isset($_COOKIE['eatable-user-session'])) {
            // Initialize the user object using the session id.
            $this->user = User::getUserBySessionId($_COOKIE['eatable-user-session']);
        }
    }

    public function invoke() {
        // Load landing page passing it session/cookie data if it is set.
        include_once('view/page/landing_page.php');
    }

}
