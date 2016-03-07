<?php

include_once('model/Owner.php');
include_once('model/Restaurant.php');
include_once('model/Table.php');
include_once('model/Reservation.php');

class BusinessController {

	private $user;
        private $restaurant;
        private $reservations;

	public function __construct() {
		// Check that cookie exists.
		if (isset($_COOKIE['eatable-user-session'])) {
			$this->user = Owner::getRestaurantOwnerBySessionId($_COOKIE['eatable-user-session']);
		} else {
			$this->user = new Owner();
		}
        
        $this->restaurant = Restaurant::getRestaurantByEmployeeId($this->user->id);
	}

	public function invoke() {
		if (!is_null($this->user) && $this->user->verify()) {
			$currentPage = "reservations";                        
                        include 'view/page/business_reservations.php';
		} else {
			include_once("controller/LandingController.php");
			$landingController = new LandingController();
			$landingController->invoke();
		}
	}
}
