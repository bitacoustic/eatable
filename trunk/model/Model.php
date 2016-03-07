<?php

require 'db/database.php';

class Model {
	public function queryDatabase($sql) {
		// Open a database connection using the credentials in db/database.php
		$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

		// Check the database connection.
		if(!$connection) {
			echo "Database Connection Error...<br>";
		}
		
		// Prepare the query.
		$connection->prepare($sql);
		
		// Query the database using sql argument.
		$results = $connection->query($sql);

		// Close the database.
		$connection->close();
		
		// Return the results object.
		return $results;
	}

	public function insertDatabase($sql) {
		// Open a database connection using the credentials in db/database.php
		$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

		// Check the database connection.
		if(!$connection) {
			echo "Database Connection Error...<br>";
		}
		
		// Execute the insert statement using sql argument.
		if($connection->query($sql)) {
			// If an id was generated, return it. Otherwise return True.
			if(is_null($connection->insert_id)) {
				return True;
			}
			else {
				return $connection->insert_id;
			}
		} else {
			return False;
		}
		// Close the database.
		$connection->close();
	}
}
