<?php

include_once 'model/Model.php';

/*
 * A restaurant menu.
 */

class Menu extends Model {
    private $id;
    private $restaurantId;
    private $categories;    // array of menu categories

    
    /**
     * Instantiates the menu object.
     * @param type $restaurantId
     * 
     */
    public function __construct($restaurantId = NIL) {
        $this->restaurantId = $restaurantId;
        $this->id = NIL;
        $this->categories = array();
    }

    /**
     * Magic getter method. 
     * @param type $property. The field to return from the object
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
    
    /*
     * Adds a menu to the database.
     */
    public static function add($menu) {
        // TODO
    }
    
    /*
     * Removes the menu with the specified ID from the database.
     */
    public static function remove($menuId) {
        // TODO
    }

}
