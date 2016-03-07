<?php

include_once('model/Administrator.php');
include_once('model/Table.php');
include_once('model/Restaurant.php');

class AdminTablesController {

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
                    case 'restaurant_id':
                    case 'table_number':
                    case 'seating_capacity':
                    case 'features':
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
                $filterValue = htmlspecialchars($_GET['filterValue']);
                if (isset($_GET['filterFlagNumber'])) {
                    $filterFlagNumber = 1;
                }
            }

            $tables = Table::getAllTables($sort, $filterProperty, $filterValue, $filterFlagNumber);
            include 'view/page/admin_tables.php';
        }
    }

    /*
     * Remove a restaurant table from the database.
     */
    public function removeTable() {
        if (!($this->user->verify())) {
            include 'view/page/landing_page.php';
        } else {
            if (isset($_GET['id'])) {
                $removedTable = new Table(htmlspecialchars($_GET['id']));
                if (Table::remove($removedTable)) {
                    $removedIdSuccess = True;
                } else {
                    $removedIdSuccess = False;
                }
            }
            $tables = Table::getAllTables();
            include 'view/page/admin_tables.php';
        }
    }

    /*
     * Add a new restaurant table to the database.
     */
    public function addTable() {
        if (!($this->user->verify())) {
            include 'view/page/landing_page.php';
        } else {
            $newTable = new Table();
            $newTable->restaurantId = intval($_POST['restaurant_id']);
            $newTable->seatingCapacity = intval($_POST['seating_capacity']);
            $newTable->tableNumber = intval($_POST['table_number']);
            $newTable->features = $_POST['features'];

            $addedSuccess = Table::add($newTable);
            $tables = Table::getAllTables();
            include 'view/page/admin_tables.php';
            
        }
    }
    
    /*
     * Change the status of the restaurant (approved/pending).
     */
//    public function setStatus() {
//        if (!($this->user->verify())) {
//            include 'view/page/landing_page.php';
//        } else {
//            if (isset($_GET['id']) && isset($_GET['status'])) {
//                $statusId = htmlspecialchars($_GET['id']);
//                $status = intval($_GET['status']);
//                
//                if (Restaurant::updateNumber($statusId, 'approved', $status)) {
//                    $setStatusSuccess = True;
//                } else {
//                    $setStatusSuccess = False;
//                }
//            }
//            $restaurants = Restaurant::getAllRestaurants();
//            include 'view/page/admin_restaurants.php';
//        }
//    }

}
