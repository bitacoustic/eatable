<?php

include_once('model/User.php');
include_once('model/Restaurant.php');
include_once('model/Reservation.php');

class ReservationFormController {

    private $user;
    private $restaurant;
    private $loginContainer;
    private $loginInfo;
//    private $registerUser;
    //private $reservation;

    public function __construct() {
        if (isset($_COOKIE['eatable-user-session'])) {
            $this->user = User::getUserBySessionId($_COOKIE['eatable-user-session']);
            $this->loginContainer='none';
            $this->loginInfo='block';
        } else {
            $this->user = new User();
            $this->loginContainer='block';
            $this->loginInfo='none';
        }
        $this->restaurant = Restaurant::getRestaurantById($_GET["restaurant-id"]);
        $this->reservation = new Reservation("id", "userid", $this->restaurant->id, $_GET['selecteddatesearch'], $_GET['selectedtimesearch'], $_GET['partysizesearch']);
    }

    public function invoke() {
        // Load the searchResults page.
        include 'view/page/reservation_form_page.php';
    }

}
