<?php

include_once('model/Administrator.php');
include_once('model/Restaurant.php');

class AdminController {

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
        // This page is for logged in administrators only.  Verify admin and redirect
        // to the landing page if they are not logged in.
        if (!is_null($this->user) && ($this->user->verify())) {
            $sort = NIL;
            if (isset($_GET['sort'])) {
                $sort = htmlspecialchars($_GET['sort']);
                switch ($sort) {
                    case 'id':
                    case 'status':
                    case 'owner':
                    case 'name':
                    case 'address_street':
                    case 'address_city':
                    case 'address_state':
                    case 'address_postal_code':
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

            $restaurants = Restaurant::getAllRestaurants($sort, $filterProperty, $filterValue, $filterFlagNumber);
            include 'view/page/admin_restaurants.php';
        } else {
            include_once("controller/LandingController.php");
            $landingController = new LandingController();
            $landingController->invoke();
        }
    }

    /*
     * Remove a restaurant from the database.
     */

    public function removeRestaurant() {
        if (!($this->user->verify())) {
            include 'view/page/landing_page.php';
        } else {
            if (isset($_GET['id'])) {
                $removedId = htmlspecialchars($_GET['id']);
                if (Restaurant::removeById($removedId)) {
                    $removedIdSuccess = True;
                } else {
                    $removedIdSuccess = False;
                }
            }
            $restaurants = Restaurant::getAllRestaurants();
            include 'view/page/admin_restaurants.php';
        }
    }

    /*
     * Add a new restaurant to the database.
     */

    public function addRestaurant() {
        if (!($this->user->verify())) {
            include 'view/page/landing_page.php';
        } else {
            $newRestaurant = new Restaurant();
            $newRestaurant->name = $_POST['name'];
            $newRestaurant->addressStreet = $_POST['address_street'];
            $newRestaurant->addressCity = $_POST['address_city'];
            $newRestaurant->addressState = $_POST['address_state'];
            $newRestaurant->addressPostalCode = intval($_POST['address_postal_code']);
            $newRestaurant->phoneNumber = $_POST['phone_number'];
            $newRestaurant->cuisineType = $_POST['cuisine_type'];
            $newRestaurant->keywords = $_POST['keywords'];

            $newImage = file_get_contents($_FILES['newimage']['tmp_name']);

            $addedSuccess = Restaurant::add($newRestaurant, $newImage);
            $restaurants = Restaurant::getAllRestaurants();
            include 'view/page/admin_restaurants.php';
        }
    }

    /*
     * Change the status of the restaurant (approved/pending).
     */

    public function setStatus() {
        if (!($this->user->verify())) {
            include 'view/page/landing_page.php';
        } else {
            if (isset($_GET['id']) && isset($_GET['status'])) {
                $statusId = htmlspecialchars($_GET['id']);
                $status = intval($_GET['status']);

                if (Restaurant::updateNumber($statusId, 'approved', $status)) {
                    $setStatusSuccess = True;
                } else {
                    $setStatusSuccess = False;
                }
            }
            $restaurants = Restaurant::getAllRestaurants();
            include 'view/page/admin_restaurants.php';
        }
    }

}
