<?php

// Validate user and get restaurant info
include_once('model/User.php');
include_once('model/Employee.php');
include_once('model/Restaurant.php');

// Show/edit/add reservations
include_once('model/Table.php');
include_once('model/Reservation.php');

// Display meal order
include_once('model/Order.php');
include_once('model/OrderItem.php');
include_once('model/MenuItem.php');

class HostController {

    private $user;
    private $restaurant;
    private $reservations;

    public function __construct() {
        // Check that cookie exists.
        if (isset($_COOKIE['eatable-user-session'])) {
            $this->user = Employee::getEmployeeBySessionId($_COOKIE['eatable-user-session']);
        } else {
            $this->user = new Employee();
        }

        $this->restaurant = Restaurant::getRestaurantByEmployeeId($this->user->id);
    }

    public function invoke() {
        if (!is_null($this->user) && $this->user->verify()) { //verified user's credentials
            date_default_timezone_set("America/Los_Angeles");

            if (isset($_POST['request_date'])) {
                $requestTimestamp = DateTime::createFromFormat("m-d-Y", htmlspecialchars($_POST['request_date']));
                $requestTimestamp = $requestTimestamp->getTimestamp();
                $requestDate = date("D, F j, Y", $requestTimestamp);

                if (date("m-d-Y") === htmlspecialchars($_POST['request_date'])) {
                    $pageTitle = "Today";
                    // don't show "reload today" button, which is dependent upon this variable being set
                    unset($_POST['request_date']);
                } else {
                    $pageTitle = "$requestDate";
                }

                $this->reservations = Reservation::getReservations($this->restaurant->id, date("Y-m-d", $requestTimestamp));
            } else {
                $pageTitle = "Today";
                $this->reservations = Reservation::getReservations($this->restaurant->id, date("Y-m-d"));
            }

            include 'view/page/host.php';
        } else {
            // failed to verify user's identity; return to the landing page
            include_once("controller/LandingController.php");
            $landingController = new LandingController();
            $landingController->invoke();
        }
    }

    public function setCancel() {
        if (!is_null($this->user) && $this->user->verify()) {
            $thisReservation = new Reservation(intval($_POST['id']));
            $thisReservation->updateStatus(Reservation::STATUS_CANCELLED);

            HostController::invoke();
        } else {
            // failed to verify user's identity; return to the landing page
            include_once("controller/LandingController.php");
            $landingController = new LandingController();
            $landingController->invoke();
        }
    }

    public function setNoShow() {
        if (!is_null($this->user) && $this->user->verify()) {
            $thisReservation = new Reservation(intval($_POST['id']));
            $thisReservation->updateStatus(Reservation::STATUS_NOSHOW);

            HostController::invoke();
        } else {
            // failed to verify user's identity; return to the landing page
            include_once("controller/LandingController.php");
            $landingController = new LandingController();
            $landingController->invoke();
        }
    }
    
    public function setCheckIn() {
        if (!is_null($this->user) && $this->user->verify()) {
            $thisReservation = new Reservation(intval($_POST['id']));
            $thisReservation->updateStatus(Reservation::STATUS_CHECKEDIN);

            HostController::invoke();
        } else {
            // failed to verify user's identity; return to the landing page
            include_once("controller/LandingController.php");
            $landingController = new LandingController();
            $landingController->invoke();
        }
    }
    
    public function setCheckInAndView() {
        if (!is_null($this->user) && $this->user->verify()) {
            $thisReservation = new Reservation(intval($_POST['id']));
            $thisReservation->updateStatus(Reservation::STATUS_CHECKEDIN);

            $loadViewId = intval($_POST['id']);
            
            date_default_timezone_set("America/Los_Angeles");

            if (isset($_POST['request_date'])) {
                $requestTimestamp = DateTime::createFromFormat("m-d-Y", htmlspecialchars($_POST['request_date']));
                $requestTimestamp = $requestTimestamp->getTimestamp();
                $requestDate = date("D, F j, Y", $requestTimestamp);

                if (date("m-d-Y") === htmlspecialchars($_POST['request_date'])) {
                    $pageTitle = "Today";
                } else {
                    $pageTitle = "$requestDate";
                }

                $this->reservations = Reservation::getReservations($this->restaurant->id, date("Y-m-d", $requestTimestamp));
            } else {
                $pageTitle = "Today";
                $this->reservations = Reservation::getReservations($this->restaurant->id, date("Y-m-d"));
            }

            include 'view/page/host.php';
        } else {
            // failed to verify user's identity; return to the landing page
            include_once("controller/LandingController.php");
            $landingController = new LandingController();
            $landingController->invoke();
        }
    }
    
    public function addReservation() {
        if (!is_null($this->user) && $this->user->verify()) {
            $tableNumber = intval($_POST['table_number']);
            $table = Table::getTableByRestaurantAndNumber($this->restaurant->id, $tableNumber);
            
            $newReservation = new Reservation();
            $newReservation->userId = Reservation::UNREGISTERED_USER_ID;
            $newReservation->restaurantId = $this->restaurant->id;
            $newReservation->date = $_POST['date'];
            $newReservation->time = str_replace("%3A", ":", $_POST['time']);
            $newReservation->partySize = $_POST['party_size'];
            $newReservation->tableId = $table->id;
            $newReservation->specialInstructions = $_POST['name'] . "::"
                . $_POST['phone_number'] . "::" . $_POST['special_instructions'];

            $addedSuccess = $newReservation->placeReservation();
            
            HostController::invoke();
        } else {
            // failed to verify user's identity; return to the landing page
            include_once("controller/LandingController.php");
            $landingController = new LandingController();
            $landingController->invoke();
        }
    }

}
