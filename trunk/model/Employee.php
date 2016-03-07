<?php

// Include base User class.
include_once('model/User.php');

/**
 * The employee class is the base class for all host and owner classes.
 * 
 * @author Sebastian Babb
 * @version 1.0
 */
class Employee extends User {
	private $restaurantId;

	/**
	 * Instantiates the employee object.
	 * 
	 * @param type $id The employee id.
	 * @param type $fname The employee's first name.
	 * @param type $lname The employee's last name.
	 * @param type $email The employee's email address (login).
	 * @param type $password The employee's password.
	 * @param type $session_id The employee's session id.
	 * @param type $phone_number The employee's phone number.
	 * @param type $restaurntaId The id of the restuant that employs the employee.
	 */
	public function __construct($id = NIL, $fname = NIL, $lname = NIL, 
								$email = NIL, $password = NIL, $session_id = NIL,
								$phone_number = NIL, $restaurantId = NIL) {
		
		// Call the parent constructor.
		parent::__construct($id, $fname, $lname, $email, $password, $session_id, $phone_number);
		
		$this->id = $id;
		$this->fname = $fname;
		$this->lname = $lname;
		$this->email = $email;
		$this->password = $password;
		$this->session_id = $session_id;
		$this->phone_number = $phone_number;
		$this->restaurantId = $restaurantId;
	}

	/**
	 * Magic getter method.
	 * @param type $property The field to return from the object.
	 * @return type The value of the specified field.
	 */
	public function __get($property) {
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
	 * Retrieves an employee object by its email password combination.
	 * 
	 * @param type $email employee's email (login).
	 * @param type $password employee's password.
	 * @return type An employee object.
	 */
	public static function getEmployeeByCredentials($email, $password) {
		// Build the sql query.
		$sql = "SELECT * FROM Users U INNER JOIN Employees E ON U.id = E.id WHERE U.email = '$email' AND U.password = '$password'";

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
				$instance->phone_number= $row['phone_number'];
				$instance->restaurantId= $row['restaurant_id'];
			}
			
			// Return the employee object.
			return $instance;
		}
	}
	
	/**
	 * Retrieves an employee object by its employee id.
	 * 
	 * @param type $id The employee id.
	 * @return \self An employee object.
	 */
	public static function getEmployeeById($id) {
		// Build the sql query.
		$sql = "SELECT * FROM Users U INNER JOIN Employees E ON U.id = E.id WHERE E.id = $id";
		
		// Query the database.
		$results = parent::queryDatabase($sql);

		// Parse the results.
		if ($results->num_rows == 1) {
			while ($row = $results->fetch_assoc()) {
				$instance = new self();
				$instance->id = $row['id'];
				$instance->fname = $row['fname'];
				$instance->lname = $row['lname'];
				$instance->email = $row['email']; // INSTANCE IS NOT BEING POPULATED WITH VALUES!!!!
				$instance->session_id = $row['session_id'];
				$instance->phone_number= $row['phone_number'];
				$instance->restaurantId= $row['restaurant_id'];
			}
			
			// Return the restaurant object.
			return $instance;
		}
	}
	
	/**
	 * Retrieves an employee object by its session id.
	 * 
	 * @param type $id The employee id.
	 * @return \self An employee object.
	 */
	public static function getEmployeeBySessionId($sessionId) {
		// Build the sql query.
		$sql = "SELECT * FROM Users U INNER JOIN Employees E ON U.id = E.id WHERE U.session_id = '$sessionId'";
		
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
				$instance->phone_number= $row['phone_number'];
				$instance->restaurantId= $row['restaurant_id'];
			}
			
			// Return the employee object.
			return $instance;
		}
	}
	
	/**
	 * Returns all of the employees for a specified restaurant.
	 * 
	 * @param type $restaurantId A restaurant id.
	 * @return array An array of employee objects for that restaurant.
	 */
	public static function getRestaurantEmployeesById($restaurantId) {
		// Build the sql query.
		$sql = "SELECT * FROM Users U INNER JOIN Employees E ON U.id = E.id WHERE E.restaurant_id = $restaurantId";
		
		// Query the database.
		$results = parent::queryDatabase($sql);

		// Parse the results.
		if ($results->num_rows > 0) {
			// Create an array to hold the employee objects.
			$employees = array();

			// Parse the results into individual employee objects and store them
			// in the employees array.
			while ($row = $results->fetch_assoc()) {
				// Build the employee object.
				$instance = new self();
				$instance->id = $row['id'];
				$instance->fname = $row['fname'];
				$instance->lname = $row['lname'];
				$instance->email = $row['email'];
				$instance->session_id = $row['session_id'];
				$instance->phone_number= $row['phone_number'];
				$instance->restaurantId= $row['restaurant_id'];

				// Add the employee object to the array.
				array_push($employees, $instance);
			}

			// Return the employee array.
			return $employees;
		}
	}

	/**
	 * Verifies the employee object is valid by comparing the object's email and
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
			$sql = "SELECT E.id FROM Users U INNER JOIN Employees E ON U.id = E.id WHERE U.session_id = '$this->session_id'";
		} else {
			// Session id cookie is not set, build the SQL to check the email and password.
			$sql = "SELECT E.id FROM Users U INNER JOIN Employees E ON U.id = E.id WHERE U.email = '$this->email' AND U.password = '$this->password'";
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
         * Add a user to the Employees table. This associates a user with
         * a particular Restaurant, but does NOT specify whether a user is
         * a Host or Business Owner. Those properties can be set by
         * Host::setHost() and Owner::setOwner(), respectively.
         * 
         * @param integer $userId - a unique user ID
         * @param integer $restaurantId - a unique Restaurant ID
         * @return true if the operation was successful, otherwise false
         */
        public static function setEmployee($userId, $restaurantId) {
            $sql = "INSERT INTO Employees (id, restaurant_id)
                VALUES ('$userId','$restaurantId');";
            return parent::insertDatabase($sql);
        }
        
        /**
         * Removes a user from the Employees table. To prevent accidental
         * deletion, the employee's correct associated restaurant ID must also
         * be supplied.
         * 
         * @param integer $userId - a unique user ID
         * @param integer $restaurantId - a unique Restaurant ID
         * @return true if the operation was successful, otherwise false
         */
        public static function unsetEmployee($userId, $restaurantId) {
            $sql = "DELETE FROM Employees
                    WHERE id = $userId 
                    AND restaurant_id = $restaurantId";
            return parent::insertDatabase($sql);
        }
}
