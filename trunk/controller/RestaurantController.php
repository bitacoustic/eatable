<?php

include_once('model/User.php');
include_once('model/Restaurant.php');
include_once('model/Reservation.php');
include_once('model/RestaurantHours.php');
include_once('model/MenuItem.php');

class RestaurantController {

    private $user;
    private $restaurant;
    private $hours;
    private $menu;

    public function __construct() {
        if (isset($_COOKIE['eatable-user-session'])) {
            $this->user = User::getUserBySessionId($_COOKIE['eatable-user-session']);
        }
        
        // Load restaurant information
        $this->restaurant = Restaurant::getRestaurantById(intval($_GET["restaurant-id"]));
        $this->hours = RestaurantHours::getHoursByRestaurantId($this->restaurant->id);
        $this->menu = MenuItem::getMenuItemsByRestaurant($this->restaurant->id);
    }

    public function invoke() {
        // Load the searchResults page.
        include 'view/page/restaurant_page.php';
    }

}
