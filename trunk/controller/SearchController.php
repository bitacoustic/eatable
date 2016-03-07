<?php

/*
 * SearchController.php
 */

include_once('model/User.php');
include_once('model/RestaurantSearch.php');
include_once('model/Reservation.php');

class SearchController {

    private $user;
    private $restaurantSearch;   // A RestaurantSearc object.
    private $restaurantResults;
    private $isCityBrowse;

    /**
     * The constructor initialize a RestaurantSearch object and
     * and array to hold the search results 
     */
    public function __construct() {
        if (isset($_COOKIE['eatable-user-session'])) {
            $this->user = User::getUserBySessionId($_COOKIE['eatable-user-session']);
        } else {
            $this->user = new User();
        }

        $this->isCityBrowse = $_GET['citybrowse'];
        if ($this->isCityBrowse == '1') {
            $this->restaurantSearch = new RestaurantSearch("", "", $_GET['textsearch']);
        } else {
            $this->restaurantSearch = new RestaurantSearch("", $_GET['textsearch'], "");
        }

        $this->restaurantResults = array();
    }

    public function invoke() {
        // Perform a search by name.
        if ($this->isCityBrowse == '1') {
            $this->restaurantResults = $this->restaurantSearch->searchByCity();
        } else {
            $this->restaurantResults = $this->restaurantSearch->searchByName();
        }
        // Load the searchResults page.
        include 'view/page/search_results_page.php';
    }
}
