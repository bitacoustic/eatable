<?php

include_once 'model/User.php';
//include_once 'model/GeneralUser.php';

class RegistrationController {

    public $user;

    // Check if the necessary fields have been filled out.
    // Create a new user object.
    public function __construct() {
        if (isset($_POST["fname"]) && isset($_POST["lname"]) &&
            isset($_POST["email"]) && isset($_POST["password"])) {
            $this->user = new User(NIL, $_POST["fname"], $_POST["lname"], $_POST["email"], $_POST["password"], NIL, $_POST["phone_number"]);
        }
    }

    // Store the new user's information in the database.
    public function registerNewUser() {
        if ($this->user->registerUser()) {
            // Log the user in, then return to the page from whence they came.
            $this->user->startSession();
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            include('view/page/failure_page.php');
        }
    }

}
