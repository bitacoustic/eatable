<?php

include_once 'model/Model.php';
include_once 'model/Employee.php';

/**
 * Restaurant class holds restaurant information.
 */
class Restaurant extends Model {

    private $id;
    private $name;
    private $addressStreet;
    private $addressCity;
    private $addressState;
    private $addressPostalCode;
    private $phoneNumber;
    private $keywords;
    private $image;
    private $aggregateRating;
    private $approved;
    private $cuisineType;

    /**
     * Construct a Restaurant object having the following properties:
     * @param integer $id
     * @param string $name
     * @param string $addressStreet
     * @param string $addressCity
     * @param string $addressState - two uppercase characters, e.g. "CA"
     * @param integer type $addressPostalCode - US Postal Code (ZIP), e.g. 94015
     * @param string $phoneNumber - of form XXX-XXX-XXXX, area code first
     * @param string $keywords - comma-delimited list of keywords
     * @param binary $image - binary corresponding to an image
     * @param string $cuisineType - one word/phrase corresponding to cuisine,
     *                              e.g. "Chinese"
     */
    public function __construct($id = NIL, $name = NIL, $addressStreet = NIL, $addressCity = NIL, $addressState = NIL, $addressPostalCode = NIL, $phoneNumber = NIL, $keywords = NIL, $image = NIL, $cuisineType = NIL) {
        $this->id = $id;
        $this->name = $name;
        $this->addressStreet = $addressStreet;
        $this->addressCity = $addressCity;
        $this->addressState = $addressState;
        $this->addressPostalCode = $addressPostalCode;
        $this->phoneNumber = $phoneNumber;
        $this->keywords = $keywords;
        $this->image = $image;
        $this->cuisineType = $cuisineType;

        // If a restaurant id is present, populate the rest of the fields
        // from the database.
        if (!is_null($id)) {
            $this->populateRestaurantFields();
        }
    }

    /**
     * If it exists, return the specified property.
     * Precondition: The property exists.
     * @param string $property   - DB column name associated with property
     * @return type              - value of property's type
     */
    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    /**
     * If it exists, set the specified property to the given value.
     * Precondition: The property exists and the value is formatted appropriately
     * for the type.
     * @param string $property   - property to assign a value to
     * @param type $value        - the value of the property
     */
    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }

    /**
     * Get all data for this Restaurant from the database. For this to work,
     * this Restaurant object need only contain a minimum of a valid, unique ID
     * in $this->id.
     */
    private function populateRestaurantFields() {
        $sql = "SELECT * FROM Restaurants R, Images I WHERE R.id = $this->id AND R.id = I.restaurant_id";
        $results = parent::queryDatabase($sql);
        if ($results->num_rows == 1) {
            while ($row = $results->fetch_assoc()) {
                $this->name = $row['name'];
                $this->addressStreet = $row ['address_street'];
                $this->addressCity = $row ['address_city'];
                $this->addressState = $row ['address_state'];
                $this->addressPostalCode = $row ['address_postal_code'];
                $this->phoneNumber = $row ['phone_number'];
                $this->keywords = $row['keywords'];
                $this->image = $row ['image'];
                $this->approved = $row ['approved'];
                $this->cuisineType = $row ['cuisine_type'];
            }
        }
    }

    /**
     * Return the restaurant specified by the given unique ID.
     * @param number $id  - an integer corresponding to an unique ID
     * @return Restaurant - the Restaurant corresponding to the unique ID
     */
    public static function getRestaurantById($id) {
        $instance = new self($id);
        $sql = "SELECT * FROM Restaurants R, Images I WHERE R.id = " . $id . " AND R.id = I.restaurant_id";
        $results = parent::queryDatabase($sql);
        if ($results && $results->num_rows == 1) {
            while ($row = $results->fetch_assoc()) {
                $instance->name = $row['name'];
                $instance->addressStreet = $row ['address_street'];
                $instance->addressCity = $row ['address_city'];
                $instance->addressState = $row ['address_state'];
                $instance->addressPostalCode = $row ['address_postal_code'];
                $instance->phoneNumber = $row ['phone_number'];
                $instance->keywords = $row['keywords'];
                $instance->image = $row ['image'];
                $instance->approved = $row ['approved'];
                $instance->cuisineType = $row ['cuisine_type'];
            }
        }

        return $instance;
    }

    /**
     * Returns the restaurant associated with the given employee ID, or NIL
     * if the given ID is not valid or not associated with a restaurant.
     * @param integer $employeeId  - the employee's unique ID number
     * @return Restaurant          - the Restaurant associated with the employee ID
     */
    public static function getRestaurantByEmployeeId($employeeId) {
        $instance = NIL;

        $sql = "SELECT * FROM Employees WHERE id = " . $employeeId;
        $results = parent::queryDatabase($sql);
        if ($results->num_rows == 1) {
            $row = $results->fetch_assoc();
            $restaurantId = intval($row ['restaurant_id']);
        } else {
            return $instance;
        }

        $sql = "SELECT * FROM Restaurants R, Images I WHERE R.id = " . $restaurantId
                . " AND R.id = I.restaurant_id";
        $results = parent::queryDatabase($sql);
        if ($results->num_rows == 1) {
            while ($row = $results->fetch_assoc()) {
                $instance = new self($restaurantId);
                $instance->name = $row['name'];
                $instance->addressStreet = $row ['address_street'];
                $instance->addressCity = $row ['address_city'];
                $instance->addressState = $row ['address_state'];
                $instance->addressPostalCode = $row ['address_postal_code'];
                $instance->phoneNumber = $row ['phone_number'];
                $instance->keywords = $row['keywords'];
                $instance->image = $row ['image'];
                $instance->approved = $row ['approved'];
                $instance->cuisineType = $row ['cuisine_type'];
            }
        }

        return $instance;
    }

    /**
     * Returns the property from the database record having the specified
     * unique ID, or NIL if the property is unavailable.
     * @param string $property  - property value to return
     * @param number $id        - unique id of a DB record
     * @return type             - a value of type corresponding to the property
     */
    public static function getPropertyById($property, $id) {
        $sql = "SELECT $property FROM Restaurants WHERE id = $id";
        $result = parent::queryDatabase($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row[$property];
        } else {
            return NIL;
        }
    }

    /**
     * Returns a list of all restaurants in the database.
     * @param type $sort              database column to sort by (NIL if sorting by default parameter ID)
     * @param type $filterProperty    database column to filter results for
     * @param type $filterValue       value for property which signals restaurant should be returned from DB query
     * @param type $filterFlagNumber  whether or not the property is a numerical value
     *                                  - NIL if not a number, any non-nil value, e.g. "true" if it is a number
     * @return array
     */
    public static function getAllRestaurants($sort = NIL, $filterProperty = NIL, $filterValue = NIL, $filterFlagNumber = NIL) {
        $allRestaurants = NIL;
        $sql = "SELECT * FROM Restaurants";
        if ($filterProperty && $filterValue) {
            if ($filterFlagNumber) {
                $sql .= " WHERE $filterProperty = $filterValue";
            } else {
                $sql .= " WHERE $filterProperty = '$filterValue'";
            }
        }
        if ($sort) {
            $sql .= " ORDER BY " . $sort;
        }

        $results = parent::queryDatabase($sql);
        if ($results && $results->num_rows > 0) {
            $allRestaurants = array();
            while ($row = $results->fetch_assoc()) {
                $instance = new self($row ['id']);
                $instance->name = $row['name'];
                $instance->addressStreet = $row ['address_street'];
                $instance->addressCity = $row ['address_city'];
                $instance->addressState = $row ['address_state'];
                $instance->addressPostalCode = $row ['address_postal_code'];
                $instance->phoneNumber = $row ['phone_number'];
                $instance->keywords = $row['keywords'];
                //$instance->image = $row ['image'];
                $instance->approved = intval($row['approved']);
                $instance->cuisineType = $row ['cuisine_type'];
                array_push($allRestaurants, $instance);
            }
        }

        return $allRestaurants;
    }

    /**
     * Adds the specified restaurant to the database.
     * @param Restaurant $restaurant - the restaurant to be added
     * @param type $img              - the associated image
     * @return string - a string describing the success or failure of the
     *                  addition operation
     */
    public static function add($restaurant, $img = NIL) {
        // Create a database connection.
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
        $rname = $conn->escape_string($restaurant->name);
        $raddr = $conn->escape_string($restaurant->addressStreet);
        $rcity = $conn->escape_string($restaurant->addressCity);
        $rstate = $conn->escape_string($restaurant->addressState);
        $rzip = $conn->escape_string($restaurant->addressPostalCode);
        $rkeywords = $conn->escape_string($restaurant->keywords);

        // Check the connection.
        if (!$conn) {
            $retval = "There was an error connecting to the database";
        } else {
            $conn->autocommit(false);

            // Add the restaurant to the db
            $sql_restaurant = "INSERT INTO Restaurants(name, address_street, address_city,
                address_state, address_postal_code, phone_number,
                cuisine_type, keywords) VALUES ('{$rname}',
                '{$raddr}', '{$rcity}',
                '{$rstate}', '{$rzip}',
                '{$restaurant->phoneNumber}', '{$restaurant->cuisineType}',
                '{$rkeywords}')";
            $result_restaurant = $conn->query($sql_restaurant);

            // Add the associated image to the db
            $imageData = $conn->escape_string($img);
            $sql_image = "INSERT INTO Images(restaurant_id, image) VALUES ({$conn->insert_id}, '$imageData')";
            $result_image = $conn->query($sql_image);

            // If both queries execute, commit the change.  Else revert.
            if ($result_restaurant && $result_image) {
                $conn->commit();
                $retval = "The new record was added to the database.";
            } else {
                $conn->rollback();
                $retval = "The new record could not be added to the database.";
            }

            $conn->autocommit(true);
            $conn->close();
        }

        return $retval;
    }
    
    public static function addEmptyRestaurant() {
        // Create a database connection.
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

        // Check the connection.
        if (!$conn) {
            $retval = "There was an error connecting to the database";
        } else {
            $conn->autocommit(false);

            // Add the restaurant to the db
            $sql_restaurant = "INSERT INTO Restaurants(name, address_street, address_city,
                address_state, address_postal_code, phone_number,
                cuisine_type, keywords) VALUES ('-',
                '(New restaurant)', '-',
                '-', '-',
                '-', '-',
                '-')";
            $result_restaurant = $conn->query($sql_restaurant);

            $restaurantId = $conn->insert_id;
            
            // Add the associated image to the db
            $sql_image = "INSERT INTO Images(restaurant_id, image) VALUES ({$restaurantId}, ' ')";
            $result_image = $conn->query($sql_image);

            // If both queries execute, commit the change.  Else revert.
            if ($result_restaurant && $result_image) {
                $conn->commit();
            } else {
                $conn->rollback();
            }

            $conn->autocommit(true);
            $conn->close();
        }

        return $restaurantId;
    }

    /**
     * Removes restaurant with specified ID from the database.
     * @param integer $id - ID of the Restaurant to remove
     * @return boolean    - true
     */
    public static function remove($id) {
        $sqlRestaurant = "DELETE FROM Restaurants WHERE id = " . $id;
        $sqlImage = "DELETE FROM Images WHERE id = " . $id;

        parent::insertDatabase($sqlRestaurant);
        parent::insertDatabase($sqlImage);

        return True;
    }

    /**
     * Update the database record referenced by the given user ID by assigning
     * a value to the given property.
     *
     * This update method is for numerical values. To update a property holding
     * a string, use User::updateString().
     *
     * @param integer $userId   - ID of Restaurant record to update
     * @param string $property  - property to update
     * @param type $value       - value of the property being updated
     * @return boolean          - true if the record was updated, otherwise false
     */
    public static function updateNumber($userId, $property, $value) {
        if (property_exists(new Restaurant(), $property)) {
            $sql = "UPDATE Restaurants SET " . $property . " = " . $value
                    . " WHERE id = " . $userId;
            return parent::queryDatabase($sql);
        } else {
            return False;
        }
    }

    /**
     * Update the database record referenced by the given user ID by assigning
     * a valueto the given property.
     *
     * This update method is for strings (the generated SQL statement encloses
     * the value in single quotes). To update a field holding a numerical
     * value, use User::updateNumber().
     *
     * @param integer $userId   - ID of Restaurant record to update
     * @param string $property  - property to update
     * @param type $value       - value of the property being updated
     * @return boolean          - true if the record was updated, otherwise false
     */
    public static function updateString($userId, $property, $value) {
        if (property_exists(new Restaurant(), $property)) {
            $sql = "UPDATE Restaurants SET " . $property . " = '" . $value
                    . "' WHERE id = " . $userId;
            return parent::queryDatabase($sql);
        } else {
            return False;
        }
    }

    /**
     * Alias for Restaurant::remove()
     */
    public static function removeById($id) {
        return Restaurant::remove($id);
    }

    /**
     * Returns base-64 encoding of image.
     * @return encoded image
     */
    public function getImage() {
        // Check and update the image type when field has been added to the database.
        return 'data:image/jpeg;base64,' . base64_encode($this->image);
    }

    /**
     * Update this Restaurant object's database entry with the information
     * contained in the object, except for the image.
     *
     * To update the image, use Restaurant::updateImage().
     *
     * @return true if the database record is successfully updated, otherwise
     *         false
     */
    public function updateRestaurant() {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
        $rname = $conn->escape_string($this->name);
        $raddr = $conn->escape_string($this->addressStreet);
        $rcity = $conn->escape_string($this->addressCity);
        $rstate = $conn->escape_string($this->addressState);
        $rzip = $conn->escape_string($this->addressPostalCode);
        $rkeywords = $conn->escape_string($this->keywords);
        $conn->close();
        
        $sql = "UPDATE Restaurants SET name = '" . $rname
                . "', address_street = '" . $raddr
                . "', address_city = '" . $rcity
                . "', address_state = '" . $rstate
                . "', address_postal_code = '" . $rzip
                . "', phone_number = '" . $this->phoneNumber
                . "', keywords = '" . $rkeywords
//                . "', aggregateRating = '" . $this->aggregateRating
                . "', approved = '" . $this->approved
                . "', cuisine_type = '" . $this->cuisineType
                . "' WHERE id = '" . $this->id
                . "'";
        if (parent::insertDatabase($sql)) {
            return True;
        } else {
            return False;
        }
    }

    /**
     * Update the image in the database with the one stored in this
     * Restaurant object, where the database record matches this object's ID.
     *
     * @return true if the database record is successfully updated, otherwise
     *         false
     */
    public function updateImage($newImage) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
        if (!$conn) {
            return False;
        } else {
            $newImage = $conn->escape_string($newImage);
            $sql = "UPDATE Images SET image = '$newImage' WHERE restaurant_id = '$this->id'";
            $success = $conn->query($sql);
            $conn->close();
            return $success;
        }
    }

}
