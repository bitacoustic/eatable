<?php

include_once('model/User.php');

/**
 * The  Administrator class.
 * 
 * @author Sebastian Babb
 * @version 1.0
 */
class Administrator extends User {

    public function __construct($id = NIL, $fname = NIL, $lname = NIL, $email = NIL, $password = NIL, $session_id = NIL, $phone_number = NIL) {
        parent::__construct($id, $fname, $lname, $email, $password, $session_id, $phone_number);
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
     * Retrieves an administrator object by its email password combination.
     * 
     * @param type $email Admin's email (login).
     * @param type $password Admin's password.
     * @return type An admin object.
     */
    public static function getAdminByCredentials($email, $password) {
        // Build the sql query.
        $sql = "SELECT * FROM Users U INNER JOIN Administrators A ON U.id = A.id WHERE U.email = '$email' AND U.password = '$password'";
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
                $instance->password = $row['password'];
                $instance->session_id = $row['session_id'];
                $instance->phone_number = $row['phone_number'];
            }

            // Return the admin object.
            return $instance;
        }
    }

    /**
     * Retrieve an admin object by its session id.
     * 
     * @param type $sessionId
     * @return type
     */
    public static function getAdminBySessionId($sessionId) {
        // Build the sql query.
        $sql = "SELECT * FROM Users U INNER JOIN Administrators A ON U.id = A.id WHERE U.session_id = '$sessionId'";
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
                $instance->password = $row['password'];
                $instance->session_id = $row['session_id'];
                $instance->phone_number = $row['phone_number'];
            }

            // Return the admin object.
            return $instance;
        }
    }

    /**
     * Retrieve an admin object by its user id.
     * 
     * @param number $userId
     * @return User
     */
    public static function getAdminByUserId($userId) {
        // Build the sql query.
        $sql = "SELECT * FROM Users U INNER JOIN Administrators A ON U.id = A.id WHERE U.id = '$userId'";
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
                $instance->password = $row['password'];
                $instance->session_id = $row['session_id'];
                $instance->phone_number = $row['phone_number'];
            }

            // Return the admin object.
            return $instance;
        }
    }

    /**
     * Overridden parent method.
     * Verifies that the administrator object is valid by comparing the object's email and
     * password, or just the session id, against the values stored in the database
     * as well as checking the administrator table for the id.
     * This method requires that the object have values assigned to both its email
     * and password fields, or its session_id field. 
     * @return boolean True if user is verified.
     */
    public function verify() {
        // Compare session_id as a binary safe string.
        if (strcmp($this->session_id, '0') !== 0) {
            // Session id cookie is set, build the SQL to validate the session id.
            $sql = "SELECT A.id FROM Users U INNER JOIN Administrators A ON U.id = A.id  WHERE session_id = '$this->session_id'";
        } else {
            // Session id cookie is not set, build the SQL to check the email and password.
            $sql = "SELECT A.id FROM Users U INNER JOIN Administrators A ON U.id = A.id  WHERE email = '$this->email' AND password = '$this->password'";
        }

        // Query the database.
        $results = parent::queryDatabase($sql);

        // There should only be one result to the query.  If there are any more,
        // something else has failed, default to to fail status - do not grant
        // user access.
        if ($results->num_rows === 1) {
            return True;
        } else {
            return False;
        }
    }

    /**
     * Adds the specified user to the Administrators table. 
     * @return true if the operation was successful, otherwise false
     */
    public static function setAdministrator($userId) {
        $sql = "INSERT INTO Administrators (id) VALUES ('$userId');";
        return parent::insertDatabase($sql);
    }

    /**
     * Remove specified user from the Administrators table. 
     * @param integer $userId - a unique user ID
     * @return true if the operation was successful, otherwise false
     */
    public static function unsetAdministrator($userId) {
        $sql = "DELETE FROM Administrators WHERE id = " . $userId;
        return parent::insertDatabase($sql);
    }

}
