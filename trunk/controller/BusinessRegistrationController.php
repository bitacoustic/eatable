<?php

include_once('model/User.php');
include_once('model/Employee.php');
include_once('model/Owner.php');
include_once('model/Host.php');
include_once('model/Restaurant.php');

include_once('model/RestaurantHours.php');
include_once('model/Table.php');

class BusinessRegistrationController {

    private $user;

    public function __construct() {
        $this->user = new User();
    }

    public function invoke() {
        include 'view/page/business_registration.php';
    }

    // Store the new user's information in the database.
    public function registerNewOwner() {
        if (isset($_POST["fname"]) && isset($_POST["lname"]) &&
                isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["phone"])) {
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
            $fname = $conn->escape_string($_POST["fname"]);
            $lname = $conn->escape_string($_POST["lname"]);
            $email = $conn->escape_string($_POST["email"]);
            $password = $conn->escape_string($_POST["password"]);
            $phoneNumber = $conn->escape_string($_POST["phone"]);
            $conn->close();

            $this->user = new User(NIL, $fname, $lname, $email, $password, NIL, $phoneNumber);
            
            $this->user->registerUser();
            $this->user->populateUserFieldsByEmail($this->user->email);
            
            Employee::setEmployee($this->user->id, Restaurant::addEmptyRestaurant());
            Owner::setOwner($this->user->id);
            Host::setHost($this->user->id);
        }
        
        $this->user->startSession();
        
        
        $currentPage = "settings";
        $this->restaurant = Restaurant::getRestaurantByEmployeeId($this->user->id);
        $this->hours = NIL;
        $this->tables = NIL;
        include_once('view/page/business_settings.php');
    }

}
