<?php

include_once 'model/Model.php';

/*
 * A menu item belonging to a category of a restaurant menu.
 */

class RestaurantHours extends Model {
    private $id;
    private $restaurantId;
    private $dayOfWeek;  
    private $startTime;      // start time in format HH:MM:SS
    private $endTime;        // end time ""

    /**
     * Initiates the RestaurantHours object
     * @param type $id
     * @param type $restaurantId
     * @param type $dayOfWeek
     * @param type $startTime
     * @param type $endTime
     */
    public function __construct($id = NIL, $restaurantId = NIL, $dayOfWeek = NIL, $startTime = NIL, $endTime = NIL) {
        $this->id = $id;
        $this->restaurantId = $restaurantId;
        $this->dayOfWeek = $dayOfWeek;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
    }
/**
 * Magic getter method.
 * @param type $property The field to return from the object.
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
     * Gets all restaurant hours for the restaurant referenced by the given
     * unique ID.
     * @param integer $restaurantId - the unique ID of a restaurant
     * @return array of RestaurantHours objects
     */
    public static function getHoursByRestaurantId($restaurantId) {
        $hours = NIL;
        $sql = "SELECT * FROM RestaurantHours WHERE restaurant_id = " . $restaurantId
                . " ORDER BY day_of_week, start_time";
        $result = parent::queryDatabase($sql);
        if ($result && $result->num_rows > 0) {
            $hours = array();
            while ($row = $result->fetch_assoc()) {
                $instance = new self(intval($row['id']));
                $instance->restaurant_id = intval($row['restaurant_id']);
                $instance->dayOfWeek = intval($row['day_of_week']);
                $instance->startTime = $row['start_time'];
                $instance->endTime = $row['end_time'];
                array_push($hours, $instance);
            }
        }
        
        return $hours;
    }
    
    /**
     * Adds the specified restaurant hours range to the database.
     * @param RestaurantHours $range
     * @return true if the addition operation was successful, otherwise false
     */
    public static function add($range) {
        $sql = "INSERT INTO RestaurantHours (restaurant_id, day_of_week, start_time, end_time) VALUES ('"
                . $range->restaurantId . "','"
                . $range->dayOfWeek . "','"
                . $range->startTime . "','"
                . $range->endTime . "')";
        return parent::insertDatabase($sql);
    }
    
    /**
     * Removes the specified restaurant hours range from the database.
     * @param integer $id - unique ID corresponding to a restaurant hours range
     * @return true if the removal operation was successful, otherwise false
     */
    public static function remove($id) {
        $sql = "DELETE FROM RestaurantHours WHERE id = " . $id;
        return parent::insertDatabase($sql);
    }
    
    /**
     * Update this RestaurantHours object's database entry with the information
     * containe in this object.
     * @return true if the update operation was successful, otherwise false
    */
    public function update() {
        $sql = "UPDATE RestaurantHours SET restaurant_id = '" . $this->restaurantId
                . "', day_of_week = '" . $this->dayOfWeek
                . "', start_time = '" . $this->startTime
                . "', end_time = '" . $this->endTime
                . "' WHERE id = '" . $this->id
                . "'";
        
        if (parent::insertDatabase($sql)) {
            return True;
        } else {
            return False;
        }
    }
    
    /**
     * Get a string corresponding to an integral day of week.
     * @param integer $dayOfWeek - day of week
     * @return string            - string corresponding to the day of the week
     */
    public static function getDayOfWeekString($dayOfWeek) {
        switch ($dayOfWeek) {
            case 0:
                return "Sunday";
            case 1:
                return "Monday";
            case 2:
                return "Tuesday";
            case 3:
                return "Wednesday";
            case 4:
                return "Thursday";
            case 5:
                return "Friday";
            case 6:
                return "Saturday";
            default:
                return;
        }
    }
    
    /**
     * Return the specified time string (e.g. "7:00 PM") as a 24-hour time
     * string (e.g. "19:00:00").
     * @param string $timeString - a time of day string in 12-hour format
     */
    public static function convertTimeString12To24($timeString) {
        return date("H:i:s", strtotime("1970-01-01 " . $timeString));
    }
    
    /**
     * Return the specified time string (e.g. "19:00:00") as a 12-hour time
     * string (e.g. "7:00 PM").
     * @param string $timeString - a time of day sting in 24-hour format
     */
    public static function convertTimeString24to12($timeString) {
        return date("g:i A", strtotime("1970-01-01" . $timeString));
    }

}
