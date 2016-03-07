<?php

// Include base User class.
include_once('model/Employee.php');

/**
 * The employee class is a base class for all hosts and owners on the site.  
 * 
 * @author Sebastian Babb
 * @version 1.0
 */
class Owner extends Employee {
    // Class fields match those of employee.

    /**
     * Instantiate the owner object. 
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
     * Verifies the owner object is valid by comparing the object's email and
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
            $sql = "SELECT email FROM Users U INNER JOIN Employees E ON U.id = E.id INNER JOIN Owners O on E.id = O.id WHERE session_id = '$this->session_id'";
        } else {
            // Session id cookie is not set, build the SQL to check the email and password.
            $sql = "SELECT email FROM Users U INNER JOIN Employees E ON U.id = E.id INNER JOIN Owners O on E.id = O.id WHERE email = '$this->email' AND password = '$this->password'";
        }
        // Query the database.
        $results = parent::queryDatabase($sql);

        // There should only be one result to the query.  If there are any more,
        // something else has failed, default to to fail status - do not grant
        // user access.
        if ($results && $results->num_rows == 1) {
            return True;
        } else {
            return False;
        }
    }

    /**
     * Retrieves an owner object by its employee id.
     * 
     * @param type $id The employee id.
     * @return \self An employee object.
     */
    public static function getOwnerById($id) {
        // Build the sql query.
        $sql = "SELECT * FROM Users U INNER JOIN Employees E ON U.id = E.id 
				INNER JOIN Owners O ON E.id = O.id WHERE E.id = '$id'";

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
     * Returns The owner for a specified restaurant.
     * 
     * @param type $restaurantId The user id of the owner.
     * @return array An array of employee objects.
     */
    public static function getRestaurantOwnerById($restaurantId) {
        // Build the sql query.
        $sql = "SELECT * FROM Users U INNER JOIN Employees E ON U.id = E.id
				INNER JOIN Owners O ON E.id = O.id WHERE E.restaurant_id = '$restaurantId'";

        // Query the database.
        $results = parent::queryDatabase($sql);

        // Parse the results.
        if ($results->num_rows == 1) {
            // Parse the results into individual host objects and store them
            // in the host array.
            while ($row = $results->fetch_assoc()) {
                // Build the owner object.
                $instance = new self();
                $instance->id = $row['id'];
                $instance->fname = $row['fname'];
                $instance->lname = $row['lname'];
                $instance->email = $row['email'];
                $instance->session_id = $row['session_id'];
                $instance->phone_number = $row['phone_number'];
                $instance->restaurantId = $row['restaurant_id'];
            }
        }

        // Return the hosts array.
        return $instance;
    }

    /**
     * Returns The owner for a specified restaurant.
     * 
     * @param integer $sessionId a session id.
     * @return array An array of employee objects.
     */
    public static function getRestaurantOwnerBySessionId($sessionId) {
        // Build the sql query.
        $sql = "SELECT * FROM Users U INNER JOIN Employees E ON U.id = E.id
				INNER JOIN Owners O ON E.id = O.id WHERE U.session_id = '$sessionId'";

        // Query the database.
        $results = parent::queryDatabase($sql);

        // Parse the results.
        if ($results->num_rows == 1) {
            // Parse the results into individual host objects and store them
            // in the host array.
            while ($row = $results->fetch_assoc()) {
                // Build the owner object.
                $instance = new self();
                $instance->id = $row['id'];
                $instance->fname = $row['fname'];
                $instance->lname = $row['lname'];
                $instance->email = $row['email'];
                $instance->session_id = $row['session_id'];
                $instance->phone_number = $row['phone_number'];
                $instance->restaurantId = $row['restaurant_id'];
            }
            // Return the hosts array.
            return $instance;
        } else {
            return NIL;
        }
    }

    /**
     * Adds the specified user to the Owners table. (User will only have access
     * to the business settings if they are also listed in the Employee table.)
     * @param integer $userId - a unique user ID
     * @return true if the operation was successful, otherwise false
     */
    public static function setOwner($userId) {
        $sql = "INSERT INTO Owners (id) VALUES ('" . $userId . "');";
        return parent::insertDatabase($sql);
    }

    /**
     * Remove specified user from the Owners table. (If the user currently has
     * an entry in the Employee table, it will still be there.)
     * @param integer $userId - a unique user ID
     * @return true if the operation was successful, otherwise false
     */
    public static function unsetOwner($userId) {
        $sql = "DELETE FROM Owners WHERE id = " . $userId;
        return parent::insertDatabase($sql);
    }

}
