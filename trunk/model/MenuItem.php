<?php

include_once 'model/Model.php';

/*
 * The Model class is a base class for all MenuItem and MenuCategory on the site.
 * A menu item belonging to a category of a restaurant menu.
 */

class MenuItem extends Model {

    private $id;
    private $restaurantId; // unique ID of the restaurant associated with this menuitem
    private $name;         // name of the item
    private $description;  // a text description of the item
    private $price;        // item's price as a decimal value, e.g. 12.75, assumed to be USD
    private $quantity;

    /**
     * Initiates the MenuItem object
     * @param type $id The MenuItem id
     * @param type $name The MenuItem's name
     * @param type $description The description of MenuItem
     * @param type $price The price of MenuItem
     * @param type $quantity The quantity of MenuItem
     */
    public function __construct($id = NIL, $restaurantId = NIL, $name = NIL, $description = NIL, $price = NIL, $quantity = NIL) {
        $this->id = $id;
        $this->restaurantId = $restaurantId;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->quantity = $quantity;
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

    /**
     * Adds this menu item to the database.
     * @param MenuItem $menuItem
     */
    public function add() {
        $sql = "INSERT INTO MenuItems (restaurant_id, name, description, price, quantity) VALUES ('"
                . $this->restaurantId . "','"
                . $this->name . "','"
                . $this->description . "','"
                . $this->price . "','"
                . $this->quantity . "')";
        return parent::insertDatabase($sql);
    }

    /**
     * Removes this menu item from the database.
     * @param MenuItem $menuItem
     */
    public function remove() {
        $sql = "DELETE FROM MenuItems WHERE id = " . $this->id;
        return parent::insertDatabase($sql);
    }

    /**
     * Updates this menu item's entry in the database with informatino stored
     * in the current object.
     */
    public function updateMenuItem() {
        $sql = "UPDATE MenuItems SET restaurant_id = '"
                . $this->restaurantId . "', name = '"
                . $this->name . "', description = '"
                . $this->description . "', price = '"
                . $this->price . "', quantity = '"
                . $this->quantity . "' WHERE id = " . $this->id;
        return parent::queryDatabase($sql);
    }

    /**
     * Retrieves all menu items associated with a unique Restaurant ID.
     * @param integer $restaurantId - unique ID or a restaurant
     * @return array of MenuItems
     */
    public static function getMenuItemsByRestaurant($restaurantId) {
        $menuItems = NIL;
        $sql = "SELECT * FROM MenuItems WHERE restaurant_id = " . $restaurantId;
        $results = parent::queryDatabase($sql);
        if ($results->num_rows > 0) {
            $menuItems = array();
            while ($row = $results->fetch_assoc()) {
                $instance = new self($row['id']);
                $instance->restaurantId = $row['restaurant_id'];
                $instance->name = $row['name'];
                $instance->description = $row['description'];
                $instance->price = $row['price'];
                $instance->id = $row['id'];
                //$instance->quantity = $row['quantity'];
                array_push($menuItems, $instance);
            }
        }
        return $menuItems;
    }

    /**
     * Returns a menu item with the specified unique ID.
     * @param integer $id - an unique MenuItem id
     * @return MenuItem with specified ID, or null if not found
     */
    public static function getMenuItemById($id) {
        $menuItem = NIL;
        $sql = "SELECT * FROM MenuItems WHERE id = " . $id;
        $results = parent::queryDatabase($sql);
        
        if ($results->num_rows == 1) {
            $row = $results->fetch_assoc();
            $instance = new self($row['id']);
            $instance->restaurantId = $row['restaurant_id'];
            $instance->name = $row['name'];
            $instance->description = $row['description'];
            $instance->price = $row['price'];
            //$instance->quantity = $row['quantity'];
        }
        
        return $instance;
    }

}
