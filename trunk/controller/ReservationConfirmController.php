<?php

include_once('model/User.php');
include_once('model/Restaurant.php');
include_once('model/Reservation.php');

class ReservationConfirmController {

    private $user;
    private $restaurant;
    private $reservation;
    private $newReservation;
    private $retval;
    private $instruction ="";
    public function __construct() {
        if (isset($_COOKIE['eatable-user-session'])) {
            $this->user = User::getUserBySessionId($_COOKIE['eatable-user-session']);
        }
        $this->restaurant = Restaurant::getRestaurantById($_GET["restaurant-id"]);
        $restaurantId = intval($_GET["restaurant-id"]);
        $partySize = intval($_GET['partySize']);
        $dateTime = DateTime::createFromFormat('m-d-Y', $_GET['selectedDate']);
        $date = $dateTime->format('Y-m-d');
        $d = DateTime::createFromFormat('H:i A ', $_GET['selectedTime']);
        $time = $d->format('H:i:s');
        //$partySize = strstr($_GET['partySize'], 'people', true);
        //search table availability
        $this->newReservation = Reservation::queryReservation($restaurantId, $date, $time, $partySize);
        if($this->newReservation->tableId != NIL){
            //$partySize = strstr($this->newReservation->partySize, 'people', true);
            if(isset($_GET['specialinstruction'])){
                $this->instruction = $_GET['specialinstruction'];
            }
            //Parse Date to MYSQL format
            //$dateTime = DateTime::createFromFormat('m-d-Y', $this->newReservation->date);
            //$date = $dateTime->format('Y-m-d');
            //Parse Time to MYSQL format
            //$d = DateTime::createFromFormat('H:i A ', $this->newReservation->time);
            //$time = $d->format('H:i:s');
            
            //Construct a new reservation based on selected information
            $this->reservation = new Reservation("", $this->user->id, $this->restaurant->id,
                    $this->newReservation->date, $this->newReservation->time,
                    $partySize, $this->newReservation->tableId, 
                    $this->instruction, $this->newReservation->status,"");
            
            //Insert a new reservation into Reservation Table
            $this->retval = $this->reservation->placeReservation();
        }
    }

    public function invoke() {
        // Load the searchResults page.
        include 'view/page/reservation_confirm_page.php';
    }

}
