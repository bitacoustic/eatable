<?php

include_once('model/User.php');
include_once('model/Restaurant.php');
include_once('model/Reservation.php');

class BusinessReservationConfirmController {

    private $user;

    //private $reservation;

    public function __construct() {
        if (isset($_COOKIE['eatable-user-session'])) {
            $this->user = User::getUserBySessionId($_COOKIE['eatable-user-session']);
        }
    }

    public function invoke() {
        // Load the searchResults page.
        include 'view/page/business_reservations_confirm_page.php';
    }

}
