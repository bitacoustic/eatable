<?php

include_once('model/Owner.php');
include_once('model/Restaurant.php');
include_once('model/MenuCategory.php');
include_once('model/MenuItem.php');

class BusinessMenuController {

    private $user;
    private $restaurant;
    private $menu;

    public function __construct() {
        // Check that cookie exists.
        if (isset($_COOKIE['eatable-user-session'])) {
            $this->user = Owner::getRestaurantOwnerBySessionId($_COOKIE['eatable-user-session']);
        } else {
            $this->user = new Owner();
        }

        $this->restaurant = Restaurant::getRestaurantByEmployeeId($this->user->id);
        $this->menu = MenuItem::getMenuItemsByRestaurant($this->restaurant->id);
    }

    public function invoke() {
        if (!($this->user->verify())) {
            include 'view/page/landing_page.php';
        } else {
            $currentPage = "menu";
            include 'view/page/business_menu.php';
        }
    }

    public function addMenuItem() {
        if (!($this->user->verify())) {
            include 'view/page/landing_page.php';
        } else {
            if (isset($_POST['restaurant_id'])) {
                $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
                
                $id = intval($_POST['restaurant_id']);
                $newItem = new MenuItem();
                $newItem->restaurantId = $id;
                $newItem->name = $conn->escape_string(htmlspecialchars($_POST['name']));
                $newItem->price = number_format(floatval($_POST['price']), 2, '.', '');
                $newItem->description = $conn->escape_string(htmlspecialchars($_POST['itemdescription']));
                $conn->close();
//                echo var_dump($newItem);
//                
//                echo "<br/>num categories: " . MenuCategory::getNumCategoriesForRestaurant($id);
                if (MenuCategory::getNumCategoriesForRestaurant($id) < 1) {
                    MenuCategory::addStarterCategory($id);
                }

                $category = MenuCategory::getStarterCategory($id);
//                echo "<br/>add to category: ";
//                echo var_dump($category);

                $addSuccess = $newItem->add($category);
//                echo "<br/>add success?: " . $addSuccess;
            }

            // Refresh list of menu items
            $this->menu = MenuItem::getMenuItemsByRestaurant($this->restaurant->id);

            $currentPage = "menu";
            include 'view/page/business_menu.php';
        }
    }

    public function removeMenuItem() {
        if (!($this->user->verify())) {
            include 'view/page/landing_page.php';
        } else {
            if (isset($_POST['id'])) {
                $removeId = intval($_POST['id']);
                $removeItem = new MenuItem($removeId);
                $removeItem->remove();
            }

            // Refresh list of menu items
            $this->menu = MenuItem::getMenuItemsByRestaurant($this->restaurant->id);

            $currentPage = "menu";
            include 'view/page/business_menu.php';
        }
    }

    public function updateMenuItem() {
        
    }

}
