<?php

include_once 'model/Model.php';

/*
 * A category of a restaurant menu (e.g. Breakfast, Dinner, Beverages).
 * The Model class is a base class for all Menu and MenuCategory on the site.
 */

class MenuCategory extends Model {
    private $id;
    private $restaurantId; // unique id of a Restaurant
    private $name;         // name of the category
    private $startTime;    // time of day menu category is first available
    private $endTime;      // time of day menu category is no longer available
    private $menuItems;    // an array of menu items belonging to the category

    /**
     * Instantiates the MenuCategory object.
     */
    public function __construct() {
        $this->id = NIL;
        $this->restaurantId = NIL;
        $this->name = NIL;
        $this->startTime = NIL;
        $this->endTimme = NIL;
        $this->menuItems = array();
    }
    /**
     * Magic getter method.
     * @param type $property The field to return from the object
     * @return type The value of the specified field.
     */
    public function __get($property) {
        //if (property_exists($this, $property) && strcmp($property, "password") != 0) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }
    /**
     * Magic setter method.
     * @param type $property The field to update in the object.
     * @param type $value The new value to update the field with.
     */
    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }
    
    // TODO: add/remove/update DB handled here, or in model Menu?
    
    public static function addStarterCategory($restaurantId) {
        $sql = "INSERT INTO MenuCategories (restaurant_id, name, start_time, end_time)
            VALUES ('$restaurantId','Starter','00:00:00','23:59:59');";
        return parent::insertDatabase($sql);
    }
    
    public static function getStarterCategory($restaurantId) {
        $sql = "SELECT id FROM MenuCategories WHERE restaurant_id = '$restaurantId' AND name = 'Starter'";
        $result = parent::queryDatabase($sql);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['id'];
        } else {
            return NIL;
        }
    }
    
    public static function getNumCategoriesForRestaurant($restaurantId) {
        $sql = "SELECT restaurant_id FROM MenuCategories WHERE restaurant_id = '$restaurantId'";
        $result = parent::queryDatabase($sql);
        if (!$result) {
            return 0;
        } else {
            return $result->num_rows;
        }
    }

}
