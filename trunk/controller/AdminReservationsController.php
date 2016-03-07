<?php

include_once('model/Administrator.php');
include_once('model/Reservation.php');

class AdminReservationsController {

    private $user;

    public function __construct() {
        // Check that cookie exists.
        if (isset($_COOKIE['eatable-user-session'])) {
            $this->user = Administrator::getAdminBySessionId($_COOKIE['eatable-user-session']);
        } else {
            $this->user = new Administrator();
        }
    }

    public function invoke() {
        if (!($this->user->verify())) {
            include 'view/page/landing_page.php';
        } else {
            $sort = NIL;
            if (isset($_GET['sort'])) {
                $sort = htmlspecialchars($_GET['sort']);
                switch ($sort) {
                    case 'id':
                    case 'user_id':
                    case 'restaurant_id':
                    case 'date':
                    case 'time':
                    case 'party_size':
                    case 'table_id':
                    case 'status_id':
                        break;
                    default:
                        $sort = 'id';
                }
            }

            $filterProperty = NIL;
            $filterValue = NIL;
            $filterFlagNumber = NIL;
            if (isset($_GET['filterProperty']) && isset($_GET['filterValue'])) {
                $filterProperty = htmlspecialchars($_GET['filterProperty']);
                $filterValue = intval(htmlspecialchars($_GET['filterValue']));
                if (isset($_GET['filterFlagNumber'])) {
                    $filterFlagNumber = 1;
                }
            }

            $reservations = Reservation::getAllReservations($sort, $filterProperty, $filterValue, $filterFlagNumber);
            include 'view/page/admin_reservations.php';
        }
    }

    /**
     * Adds a new reservation with manually-specified parameters. This can be
     * used as a template for general user reservation implementation.
     */
    public function addReservation() {
        if (!($this->user->verify())) {
            include 'view/page/landing_page.php';
        } else {
            $newReservation = new Reservation();
            $newReservation->userId = intval($_POST['user_id']);
            $newReservation->restaurantId = intval($_POST['restaurant_id']);
            $newReservation->date = $_POST['date'];
            $newReservation->time = str_replace("%3A", ":", $_POST['time']);
            $newReservation->partySize = $_POST['party_size'];
            $newReservation->tableId = intval($_POST['table_id']);
            $newReservation->specialInstructions = $_POST['special_instructions'];
            $newReservation->mealOrder = $_POST['meal_order'];
            $newReservation->status = $_POST['status_id'];

            $addedSuccess = $newReservation->placeReservation();
            $reservations = Reservation::getAllReservations();
            include 'view/page/admin_reservations.php';
        }
    }

    /**
     * Display the results of a reservation availablity search.
     */
    public function checkAvailability() {
        if (!($this->user->verify())) {
            include 'view/page/landing_page.php';
        } else {
            echo "<h3>Check availability debug</h3>";
            $restaurantId = intval($_POST['restaurant_id']);
            $date = $_POST['date'];
            $time = str_replace("%3A", ":", $_POST['time']);
            $partySize = $_POST['party_size'];

            if (isset($_POST['debug']) && strcmp($_POST['debug'], "true") == 0) {
                $debug = 1;
            } else {
                $debug = NIL;
            }

            echo "<p><strong>Parameters</strong>: restaurant_id: $restaurantId, date: $date, time: $time, party_size: $partySize</p>";

            $reservation = Reservation::queryReservation($restaurantId, $date, $time, $partySize, $debug);

            echo "<h4>If a reservation is available, it will be shown here, otherwise the result will be empty:</h4>";
            echo "<p><pre>";
            echo var_dump($reservation);
            echo "</pre></p>";

            echo "<p><a href='index.php?controller=AdminReservations&action=invoke'>Return to Administrator Portal</a></p>";
        }
    }

    /**
     * Remove a reservation from the database.
     */
    public function removeReservation() {
        if (!($this->user->verify())) {
            include 'view/page/landing_page.php';
        } else {
            if (isset($_GET['id'])) {
                $removedId = htmlspecialchars($_GET['id']);
                if (Reservation::removeById($removedId)) {
                    $removedIdSuccess = True;
                } else {
                    $removedIdSuccess = False;
                }
            }
            $reservations = Reservation::getAllReservations();
            include 'view/page/admin_reservations.php';
        }
    }

}
