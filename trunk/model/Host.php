<?php

// Include base User class.
include_once('model/Employee.php');

/**
 * The employee class is a base class for all hosts and owners on the site.  
 * 
 * @author Sebastian Babb
 * @version 1.0
 */
class Host extends Employee {
    // Class fields match those of employee.

    /**
     * Instantiates the host object. 
     * 
     * @param type $id
     * @param type $fname
     * @param type $lname
     * @param type $email
     * @param type $password
     * @param type $session_id
     * @param type $phone_number
     * @param type $restaurntaId
     */
    public function __construct($id = NIL, $fname = NIL, $lname = NIL, $email = NIL, $password = NIL, $session_id = NIL, $phone_number = NIL, $restaurantId = NIL) {
        parent::__construct($id, $fname, $lname, $email, $password, $session_id, $phone_number, $restaurantId);
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
     * Verifies the host object is valid by comparing the object's email and
     * password, or just the session id, against the values stored in the database.
     * This method requires that the object have values assigned to both its email
     * and password fields, or its session_id field. 
     * 
     * @return boolean True if the employee is verified.
     */
    public function verify() {
        // Compare session_id as a binary safe string.
        if (strcmp($this->session_id, '0') !== 0) {
            // Session id cookie is set, build the SQL to validate the session id.
            $sql = "SELECT id FROM Users U INNER JOIN Employees E ON U.id = E.id INNER JOIN Hosts H on E.id = H.id WHERE session_id = '$this->session_id'";
        } else {
            // Session id cookie is not set, build the SQL to check the email and password.
            $sql = "SELECT id FROM Users U INNER JOIN Employees E ON U.id = E.id INNER JOIN Hosts H on E.id = H.id WHERE email = '$this->email' AND password = '$this->password'";
        }
        // Query the database.
        $results = parent::queryDatabase($sql);

        // There should only be one result to the query.  If there are any more,
        // something else has failed, default to to fail status - do not grant
        // user access.
        if ($results->num_rows == 1) {
            return True;
        } else {
            return False;
        }
    }

    /**
     * Retrieves a host object by its employee id.
     * 
     * @param type $id The employee id.
     * @return \self An employee object.
     */
    public static function getHostById($id) {
        // Build the sql query.
        $sql = "SELECT * FROM Users U
            INNER JOIN Employees E ON U.id = E.id 
            INNER JOIN Hosts H ON E.id = H.id
            WHERE E.id = $id";

        // Query the database.
        $results = parent::queryDatabase($sql);

        // Parse the results.
        if ($results->num_rows == 1) {
            while ($row = $results->fetch_assoc()) {
                $instance = new self();
                $instance->id = $row['id'];
                $instance->fname = $row['fname'];
                $instance->lname = $row['lname'];
                $instance->email = $row['email'];
                $instance->session_id = $row['session_id'];
                $instance->phone_number = $row['phone_number'];
                $instance->restaurantId = $row['restaurant_id'];
            }
            // Return the host object.
            return $instance;
        } else {
            // User ID does not correspond to an account with host
            // permissions, or user ID is invalid.
            return NIL;
        }
    }

    /**
     * Returns all of the hosts for a specified restaurant.
     * 
     * @param type $restaurantId The restaurant id of the restaurant.
     * @return array An array of employee objects.
     */
    public static function getHostsByRestaurantId($restaurantId) {
        // Build the sql query.
        $sql = "SELECT * FROM Users U INNER JOIN Employees E ON U.id = E.id
				INNER JOIN Hosts H ON E.id = H.id WHERE E.restaurant_id = $restaurantId";

        // Query the database.
        $results = parent::queryDatabase($sql);

        // Parse the results.
        if ($results->num_rows > 0) {
            // Create an array to hold the host objects.
            $restaurantHosts = array();

            // Parse the results into individual host objects and store them
            // in the host array.
            while ($row = $results->fetch_assoc()) {
                // Build the host object.
                $instance = new self();
                $instance->id = $row['id'];
                $instance->fname = $row['fname'];
                $instance->lname = $row['lname'];
                $instance->email = $row['email'];
                $instance->session_id = $row['session_id'];
                $instance->phone_number = $row['phone_number'];
                $instance->restaurantId = $row['restaurant_id'];

                // Add the employee object to the array.
                array_push($restaurantHosts, $instance);
            }

            // Return the hosts array.
            return $restaurantHosts;
        } else {
            return NIL;
        }
    }

    /**
     * Adds the specified user to the Hosts table. (User will only have access
     * to the Host interface if they are also listed in the Employee table.)
     * @param integer $userId - a unique user ID
     * @return true if the operation was successful, otherwise false
     */
    public static function setHost($userId) {
        $sql = "INSERT INTO Hosts (id) VALUES ('$userId');";
        return parent::insertDatabase($sql);
    }

    /**
     * Remove specified user from the Hosts table. (If the user currently has
     * an entry in the Employee table, it will still be there.)
     * @param integer $userId - a unique user ID
     * @return true if the operation was successful, otherwise false
     */
    public static function unsetHost($userId) {
        $sql = "DELETE FROM Hosts WHERE id = " . $userId;
        return parent::insertDatabase($sql);
    }

}
