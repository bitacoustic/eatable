<?php

include_once('model/User.php');

class LogoutController {

    public $user;

    /**
     * The constructor initialize a new user object
     */
    public function __construct() {
        if (isset($_COOKIE['eatable-user-session'])) {
            //Initialize the user object using the session id.
            $this->user = User::getUserBySessionId($_COOKIE['eatable-user-session']);
        }
    }

    /**
     * 
     */
    public function invoke() {
        // Check that the user has a session id.
        if ($_COOKIE['eatable-user-session']) {
            $this->user->endSession();
            // Return to the landing page
            header('Location: index.php');
        }
    }

}
