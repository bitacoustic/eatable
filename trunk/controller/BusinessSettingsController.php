<?php

include_once('model/Owner.php');
include_once('model/Restaurant.php');
include_once('model/RestaurantHours.php');
include_once('model/Table.php');

class BusinessSettingsController {

    private $user;
    private $restaurant;
    private $hours;
    private $tables;

    public function __construct() {
        // Check that cookie exists.
        if (isset($_COOKIE['eatable-user-session'])) {
            $this->user = Owner::getRestaurantOwnerBySessionId($_COOKIE['eatable-user-session']);
        } else {
            $this->user = new Owner();
        }

        if ($this->user->isOwner()) {
            $this->restaurant = Restaurant::getRestaurantByEmployeeId($this->user->id);
            $this->hours = RestaurantHours::getHoursByRestaurantId($this->restaurant->id);
            $this->tables = Table::getTablesByRestaurant($this->restaurant->id);
        }
    }

    public function invoke() {
        if (!($this->user->verify())) {
            include 'view/page/landing_page.php';
        } else {

            $currentPage = "settings";
            include 'view/page/business_settings.php';
        }
    }

    public function updateRestaurant() {
        if (!($this->user->verify())) {
            include 'view/page/landing_page.php';
        } else {
            $currentPage = "settings";

            $this->restaurant->name = htmlspecialchars($_POST['rname']);
            $this->restaurant->addressStreet = htmlspecialchars($_POST['address']);
            $this->restaurant->addressCity = htmlspecialchars($_POST['city']);
            $this->restaurant->addressState = htmlspecialchars($_POST['state']);
            $this->restaurant->addressPostalCode = htmlspecialchars($_POST['zip']);
            $this->restaurant->phoneNumber = htmlspecialchars($_POST['phone']);
            $this->restaurant->cuisineType = htmlspecialchars($_POST['cuisine']);
            $this->restaurant->keywords = htmlspecialchars($_POST['keywords']);

            $updateSuccess = $this->restaurant->updateRestaurant();
            include 'view/page/business_settings.php';
        }
    }

    /**
     * Update the owner's restaurant's image.
     */
    public function updateImage() {
        if (!($this->user->verify())) {
            include 'view/page/landing_page.php';
        } else {
            $currentPage = "settings";

            $newImage = file_get_contents($_FILES['newimage']['tmp_name']);
            $updateImgSuccess = $this->restaurant->updateImage($newImage);

            // refresh this restaurant
            $this->restaurant = Restaurant::getRestaurantByEmployeeId($this->user->id);

            include 'view/page/business_settings.php';
        }
    }

    /**
     * Remove from the database a restaurant table associated with this restaurant.
     */
    public function removeTable() {
        if (!($this->user->verify())) {
            include 'view/page/landing_page.php';
        } else {
            // Remove table with specified ID
            $removeTableId = intval($_POST['id']);
            $removeTableSuccess = Table::remove(new Table($removeTableId));
            $removeTableNumber = Table::getTableNumberById($removeTableId);

            // Refresh tables
            $this->tables = Table::getTablesByRestaurant($this->restaurant->id);

            include 'view/page/business_settings.php';
        }
    }

    /**
     * Add to the database a new restaurant table to be associated with this restaurant.
     */
    public function addTable() {
        if (!($this->user->verify())) {
            include 'view/page/landing_page.php';
        } else {
            $newTable = new Table();
            $newTable->restaurantId = $this->restaurant->id;
            $newTable->seatingCapacity = intval($_POST['seating_capacity']);
            $newTable->tableNumber = intval($_POST['table_number']);
//            $newTable->features = $_POST['features'];
            $newTable->features = "";

            $tableAlreadyExists = False;
            if ($this->tables) {
                foreach ($this->tables as $table) {
                    if ($table->tableNumber === $newTable->tableNumber) {
                        $tableAlreadyExists = True;
                        break;
                    }
                }
            }

            if (!$tableAlreadyExists) {
                $addedTableSuccess = Table::add($newTable);

                // Refresh tables
                $this->tables = Table::getTablesByRestaurant($this->restaurant->id);
            } else { // table already exists
                $addedTableSuccess = False;
            }

            $currentPage = "settings";
            include 'view/page/business_settings.php';
        }
    }

    /**
     * Add to the database a number of restaurant tables to be associated with this restaurant.
     */
    public function addTables() {
        if (!($this->user->verify())) {
            include 'view/page/landing_page.php';
        } else {
            // Get the starting index and table number bounds for the new tables
            $numConsecutive = intval($_POST['num_consecutive']);
            $startTableNumber = intval($_POST['table_number']);
            $endTableNumber = $numConsecutive + $startTableNumber - 1;

            // Don't do the operation if any of the table numbers to be added already exist.
            // This might happen if the page is refreshed after an addTables() operation, which
            // would repeat the operation with the same starting table number.
            $tableAlreadyExists = False;
            foreach ($this->tables as $table) {
                if ($table->tableNumber >= $startTableNumber && $table->tableNumber <= $endTableNumber) {
                    $tableAlreadyExists = True;
                    break;
                }
            }

            if (!$tableAlreadyExists) {
                // Add the tables, flagging $addTableSuccess = False if any of the
                // database additions were not completed successfully
                $addTableSuccess = True;
                for ($i = $startTableNumber; $i <= $endTableNumber; $i++) {
                    $newTable = new Table();
                    $newTable->restaurantId = $this->restaurant->id;
                    $newTable->seatingCapacity = intval($_POST['seating_capacity']);
                    $newTable->tableNumber = $i;
                    //            $newTable->features = $_POST['features'];
                    $newTable->features = "";
                    if (!Table::add($newTable)) {
                        $addTableSuccess = False;
                    }
                }
            } else { // table already exists
                $addTableSuccess = False;
            }

            // Refresh tables
            $this->tables = Table::getTablesByRestaurant($this->restaurant->id);

            $currentPage = "settings";
            include 'view/page/business_settings.php';
        }
    }

    /**
     * Update the seating capacity of the specified table.
     */
    public function editTable() {
        if (!($this->user->verify())) {
            include 'view/page/landing_page.php';
        } else {
            $currentPage = "settings";

            // Get the table at this restaurant with the specified table number,
            // update its seating capacity, and then write this information to the database.
            $editTable = Table::getTableByRestaurantAndNumber($this->restaurant->id, intval($_POST['table_number']));
            $editTable->seatingCapacity = intval($_POST['seating_capacity']);
            $editTableSuccess = $editTable->updateTable();

            // Refresh tables
            $this->tables = Table::getTablesByRestaurant($this->restaurant->id);

            include 'view/page/business_settings.php';
        }
    }

    /**
     * Add a new open hours range to the database
     */
    public function addRange() {
        if (!($this->user->verify())) {
            include 'view/page/landing_page.php';
        } else {
            $newRange = new RestaurantHours(NIL);
            $newRange->restaurantId = $this->restaurant->id;
            $newRange->dayOfWeek = intval($_POST['day']);
            $newRange->startTime = RestaurantHours::convertTimeString12To24(htmlspecialchars($_POST['start']));
            $newRange->endTime = RestaurantHours::convertTimeString12To24(htmlspecialchars($_POST['end']));

            $addRangeSuccess = RestaurantHours::add($newRange);

            // Refresh restaurant hours
            $this->hours = RestaurantHours::getHoursByRestaurantId($this->restaurant->id);

            $currentPage = "settings";
            include 'view/page/business_settings.php';
        }
    }

    /**
     * Remove a restaurant hours range from the database.
     */
    public function removeRange() {
        if (!($this->user->verify())) {
            include 'view/page/landing_page.php';
        } else {
            $removeRangeSuccess = RestaurantHours::remove(intval($_POST['id']));

            // Refresh restaurant hours
            $this->hours = RestaurantHours::getHoursByRestaurantId($this->restaurant->id);

            $currentPage = "settings";
            include 'view/page/business_settings.php';
        }
    }

    /**
     * Edit a restaurant hours range by updating its start and end time.
     */
    public function editRange() {
        if (!($this->user->verify())) {
            include 'view/page/landing_page.php';
        } else {
            $newRange = new RestaurantHours();
            $newRange->id = intval($_POST['id']);
            $newRange->restaurantId = $this->restaurant->id;
            $newRange->dayOfWeek = intval($_POST['day']);
            $newRange->startTime = RestaurantHours::convertTimeString12To24(htmlspecialchars($_POST['start']));
            $newRange->endTime = RestaurantHours::convertTimeString12To24(htmlspecialchars($_POST['end']));

            $editRangeSuccess = $newRange->update();

            // Refresh restaurant hours
            $this->hours = RestaurantHours::getHoursByRestaurantId($this->restaurant->id);

            $currentPage = "settings";
            include 'view/page/business_settings.php';
        }
    }

}
