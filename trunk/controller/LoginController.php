<?php

/*
 * LoginController.php
 */

// Include the user class.
include_once('model/User.php');

class LoginController {

    private $user;

    /**
     *  The constructor initialize a new user object.
     */
    public function __construct() {
        // Check if post data was successfully passed from the landing page.
        if (isset($_POST["email"]) && isset($_POST["password"])) {
            // Initialize the a new user object using email and password.
            $this->user = User::getUserByCredentials($_POST["email"], $_POST["password"]);
        }
    }

    public function invoke() {
        // Verify the user's credentials.
        // Ensure that the user object is not null.
        if (!is_null($this->user) && $this->user->verify()) {
            // Start a new user session.
            $this->user->startSession();
            // Reload the page the user was on when they logged in.
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            // Load the failed login page.
            include_once('view/page/failure_page.php');
        }
    }

}
