<?php

// Include base model class.
include_once('model/Model.php');

/**
 * The user class is a base class for all users on the site.  Hosts, owners,
 * admins, and generalUsers all inherit this class.
 * 
 * @author Sebastian Babb
 * @version 1.0
 */
class User extends Model {

    protected $id;
    protected $fname;
    protected $lname;
    protected $email;
    protected $password; // Should be hashed in deployment.
    protected $session_id;
    protected $phone_number;

    /**
     * Instantiates a user object.
     */
    public function __construct($id = NIL, $fname = NIL, $lname = NIL, $email = NIL, $password = NIL, $session_id = NIL, $phone_number = NIL) {
        $this->id = $id;
        $this->fname = $fname;
        $this->lname = $lname;
        $this->email = $email;
        $this->password = $password;
        $this->session_id = $session_id;
        $this->phone_number = $phone_number;
    }

    /**
     * Magic getter method.form-name
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
     * Verifies if the user object is valid by comparing the object's email and
     * password, or just the session id, against the values stored in the database.
     * This method requires that the object have values assigned to both its email
     * and password fields, or its session_id field. 
     * @return boolean True if user is verified.
     */
    public function verify() {
        // Compare session_id as a binary safe string.
        if (!is_null($this->session_id) && strcmp($this->session_id, '0') !== 0) {
            // Session id cookie is set, build the SQL to validate the session id.
            $sql = "SELECT id FROM Users WHERE session_id = '$this->session_id'";
        } else {
            // Session id cookie is not set, build the SQL to check the email and password.
            $sql = "SELECT id FROM Users WHERE email = '$this->email' AND password = '$this->password'";
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
     * Retrieves a user object by its email password combination.
     * 
     * @param type $id The employee id.
     * @return \self An employee object.
     */
    public static function getUserByCredentials($email, $password) {
        // Build the sql query.
        $sql = "SELECT * FROM Users WHERE email = '$email' AND password = '$password'";
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

            // Return the user object.
            return $instance;
        }
    }

    /**
     * Retrieves a user object by its session id.
     * 
     * @param type $id The employee id.
     * @return \self An employee object.
     */
    public static function getUserBySessionId($sessionId) {
        // Build the sql query.
        $sql = "SELECT * FROM Users WHERE session_id = '$sessionId'";

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

            // Return the user object.
            return $instance;
        }
    }

    /**
     * Retrieves a user object by its id.
     * 
     * @param type $id The user id.
     * @return \self A User object.
     */
    public static function getUserById($id) {
        // Build the sql query.
        $sql = "SELECT * FROM Users WHERE id = $id";

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

            // Return the user object.
            return $instance;
        }
    }

    /**
     * Confirms the user's password.
     */
    public function confirmPassword($password) {
        $sql = "SELECT id FROM Users WHERE email = '$this->email' AND password = '$password'";

        // Query the database.
        $results = parent::queryDatabase($sql);

        // Return results.
        if ($results->num_rows == 1) {
            return True;
        } else {
            return False;
        }
    }

    /**
     * Starts a new session for the user.
     */
    public function startSession() {
        // Create a unique session id from the object's email field.
        $this->session_id = md5(uniqid($this->email, True));

        // Build the SQL to update the user's record with the new session id.
        $sql = "UPDATE Users SET session_id = '$this->session_id ' WHERE email = '$this->email' AND password = '$this->password'";

        // Execute the insert statement.
        parent::insertDatabase($sql);

        // Store the session id in the cookie.
        setcookie("eatable-user-session", $this->session_id);
    }

    /**
     * Ends the user's current session.
     */
    public function endSession() {
        // Store the session id in the database.
        $sql = "UPDATE Users SET session_id = NULL WHERE session_id = '$this->session_id'";
        parent::insertDatabase($sql);

        // Store session id in the users cookie.
        setcookie('eatable-user-session', '', time() - 3600);
    }

    /**
     * Registers the current user object - stores it in the database.
     * @return boolean
     */
    public function registerUser() {
        // Ensure that at least an email and password are provided.
        // Avoid empty field user.
        if (!is_null($this->email) && !is_null($this->password)) {
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
            $fname = $conn->escape_string($this->fname);
            $lname = $conn->escape_string($this->lname);
            $conn->close();
            
            $sql = "INSERT INTO Users (fname, lname, email, password, phone_number) VALUES ('$fname', '$lname', '$this->email', '$this->password', '$this->phone_number')";
            if (parent::insertDatabase($sql)) {
                return True;
            } else {
                return False;
            }
        }
    }

    /**
     * Updates the user's basic information - fname, lname, email, ect...
     * @return boolean
     */
    public function updateUser() {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
        $fname = $conn->escape_string($this->fname);
        $lname = $conn->escape_string($this->lname);
        $conn->close();
        
        $sql = "UPDATE Users SET fname = '$this->fname', lname = '$this->lname', email = '$this->email', phone_number = '$this->phone_number' WHERE session_id = '$this->session_id'";
        if (parent::insertDatabase($sql)) {
            return True;
        } else {
            return False;
        }
    }

    /**
     * Updates the user's password.
     * @return boolean
     */
    public function updateUserPassword() {
        $sql = "UPDATE Users SET password = '$this->password' WHERE session_id = '$this->session_id'";
        if (parent::insertDatabase($sql)) {
            return True;
        } else {
            return False;
        }
    }

    /**
     * 	Populates all the fields in the user object using the session_id.
     *  
     * @return boolean True if it successfully populates the user object.
     */
    public function populateUserFields() {
        $sql = "SELECT * FROM Users WHERE session_id = '$this->session_id'";
        $results = parent::queryDatabase($sql);
        if ($results->num_rows == 1) {
            while ($row = $results->fetch_assoc()) {
                $this->id = $row['id'];
                $this->fname = $row['fname'];
                $this->lname = $row['lname'];
                $this->email = $row['email'];
                $this->password = $row['password'];
                $this->session_id = $row['session_id'];
                $this->phone_number = $row['phone_number'];
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * 	Populates all the fields in the user object using the email address
     * (unique ID) stored in this object.
     *  
     * @return boolean True if it successfully populates the user object.
     */
    public function populateUserFieldsByEmail() {
        $sql = "SELECT * FROM Users WHERE email = '$this->email'";
        $results = parent::queryDatabase($sql);
        if ($results->num_rows == 1) {
            while ($row = $results->fetch_assoc()) {
                $this->id = $row['id'];
                $this->fname = $row['fname'];
                $this->lname = $row['lname'];
                $this->email = $row['email'];
                $this->password = $row['password'];
                $this->session_id = $row['session_id'];
                $this->phone_number = $row['phone_number'];
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * 
     * @param type $sort
     * @return array
     */
    public static function getAllUsers($sort = NIL) {
        $allUsers = NIL;
        $sql = "SELECT * FROM Users";
        if ($sort) {
            $sql .= " ORDER BY " . $sort;
        }
        $results = parent::queryDatabase($sql);

        if ($results && $results->num_rows > 0) {
            $allUsers = array();
            while ($row = $results->fetch_assoc()) {
                $instance = new self();
                $instance->id = $row['id'];
                $instance->fname = $row['fname'];
                $instance->lname = $row['lname'];
                $instance->email = $row['email'];
                $instance->session_id = $row['session_id'];
                $instance->phone_number = $row['phone_number'];
                array_push($allUsers, $instance);
            }
        }

        return $allUsers;
    }

    /**
     * Update the database record referenced by the given user ID by assigning
     * a value to the given property.
     * 
     * This update method is for numerical values. To update a record holding
     * a string, use User::updateString().
     */
    public static function updateNumber($userId, $property, $value) {
        if (property_exists($this, $property)) {
            $sql = "UPDATE Users SET $property = $value WHERE id = $userId";
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
     * the value in single quotes). To update a record holding a numerical 
     * value, use User::updateNumber().
     */
    public static function updateString($userId, $property, $value) {
        if (property_exists($this, $property)) {
            $sql = "UPDATE Users SET $property = $value WHERE id = $userId";
            return parent::queryDatabase($sql);
        } else {
            return False;
        }
    }

    /**
     * Remove a user by email address (an unique ID).
     * Returns true if the removal operation is successful, otherwise false.
     */
    public static function removeByEmail($email) {
        $sql = "DELETE FROM Users WHERE email = $email";
        return parent::queryDatabase($sql);
    }

    /**
     * Remove a user by email address (an unique ID).
     * Returns true if the removal operation is successful, otherwise false.
     */
    public static function removeById($id) {
        $sql = "DELETE FROM Users WHERE id = $id";
        return parent::queryDatabase($sql);
    }

    /**
     * Returns whether or not the user is a host.
     * @param type $id - a unique user ID
     * @return true or false
     */
    public function isHost() {
        $sql = "SELECT * FROM Hosts WHERE id = " . $this->id;
        $result = parent::queryDatabase($sql);
        return $result->num_rows > 0;
    }

    /**
     * Returns whether or not the user is a business owner.
     * @param type $id - a unique user ID
     * @return true or false
     */
    public function isOwner() {
        $sql = "SELECT * FROM Owners WHERE id = " . $this->id;
        $result = parent::queryDatabase($sql);
        return $result->num_rows > 0;
    }

    /**
     * Returns whether or not the user is an administrator.
     * @param type $id - a unique user ID
     * @return true or false
     */
    public function isAdmin() {
        $sql = "SELECT * FROM Administrators WHERE id = " . $this->id;
        $result = parent::queryDatabase($sql);
        return $result->num_rows > 0;
    }

}
