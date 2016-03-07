<?php

include_once 'model/Model.php';

/*
 * A table in a restaurant.
 */

class Table extends Model {

    private $id;
    private $restaurantId;
    private $seatingCapacity;
    private $tableNumber;
    private $features;

    public function __construct($id = NIL, $restaurantId = NIL, $seatingCapacity = NIL, $tableNumber = NIL, $features = NIL) {
        $this->id = $id;
        $this->restaurantId = $restaurantId;
        $this->seatingCapacity = $seatingCapacity;
        $this->tableNumber = $tableNumber;
        $this->features = $features;
    }

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }

    // Adds the specified table to the database. Returns true if the table
    // was successfully written to the database, otherwise false.
    public static function add($table) {
        $sql = "INSERT INTO Tables (restaurant_id, seating_capacity, table_number, features) VALUES ("
                . $table->restaurantId . ","
                . $table->seatingCapacity . ","
                . $table->tableNumber . ","
                . "'" . $table->features . "');";
        return parent::insertDatabase($sql);
    }

    // Removes the specified table from the database. Returns the Table object
    // if it was successfully removed from the database, otherwise NIL.
    public static function remove($table) {
        $sql = "DELETE FROM Tables WHERE id = " . $table->id;
        if (parent::insertDatabase($sql)) {
            return $table;
        } else {
            return NIL;
        }
    }

    public function updateTable() {
        $sql = "UPDATE Tables SET restaurant_id = '" . $this->restaurantId
                . "', seating_capacity = '" . $this->seatingCapacity
                . "', table_number = '" . $this->tableNumber
                . "' WHERE id = " . $this->id;
        return parent::queryDatabase($sql);
    }

    /*
     * Update the database record referenced by the given user ID by assigning
     * a valueto the given property.
     * 
     * This update method is for strings (the generated SQL statement encloses
     * the value in single quotes). To update a field holding a numerical 
     * value, use Table::updateNumber().
     */

    public static function updateString($tableId, $property, $value) {
        if (property_exists(new Table(), $property)) {
            $sql = "UPDATE Tables SET " . $property . " = '" . $value
                    . "' WHERE id = " . $tableId;
            return parent::queryDatabase($sql);
        } else {
            return False;
        }
    }

    // Returns an array of tables associated with specified restaurant ID, or
    // NIL if either restaurant has no associated tables or restaurant doesn't exist.
    public static function getTablesByRestaurant($restaurantId) {
        $tables = NIL;
        $sql = "SELECT * FROM Tables WHERE restaurant_id = " . $restaurantId
                . " ORDER BY table_number ASC";
        $results = parent::queryDatabase($sql);
        if ($results->num_rows > 0) {
            $tables = array();
            while ($row = $results->fetch_assoc()) {
                $instance = new self($row['id']);
                $instance->restaurantId = $row['restaurant_id'];
                $instance->seatingCapacity = $row['seating_capacity'];
                $instance->tableNumber = $row['table_number'];
                $instance->features = $row['features'];
                array_push($tables, $instance);
            }
        }
        return $tables;
    }

    // Returns the table at the specified restaurant with the specified
    // table number, or NIL if either such a table does not exist or there
    // is more than one match.
    // Assertion: Each table at a restaurant has a unique table number.
    public static function getTableByRestaurantAndNumber($restaurantId, $tableNumber) {
        $table = NIL;
        $sql = "SELECT * FROM Tables WHERE restaurant_id = " . $restaurantId
                . " AND table_number = " . $tableNumber;
        $results = parent::queryDatabase($sql);
        if ($results->num_rows == 1) {
            $row = $results->fetch_assoc();
            $table = new self($row['id']);
            $table->restaurantId = $row['restaurant_id'];
            $table->seatingCapacity = $row['seating_capacity'];
            $table->tableNumber = $row['table_number'];
            $table->features = $row['features'];
        }
        return $table;
    }

    public static function getTableNumberById($tableId) {
        $tableNumber = NIL;
        $sql = "SELECT table_number FROM Tables WHERE id = " . $tableId;
        $results = parent::queryDatabase($sql);
        if ($results->num_rows == 1) {
            $row = $results->fetch_assoc();
            $tableNumber = $row['table_number'];
        }

        return $tableNumber;
    }

    public static function getAllTables($sort = NIL, $filterProperty = NIL, $filterValue = NIL, $filterFlagNumber = NIL) {
        $tables = NIL;
        $sql = "SELECT * FROM Tables";
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
        if ($results->num_rows > 0) {
            $tables = array();
            while ($row = $results->fetch_assoc()) {
                $instance = new self($row['id']);
                $instance->restaurantId = $row['restaurant_id'];
                $instance->seatingCapacity = $row['seating_capacity'];
                $instance->tableNumber = $row['table_number'];
                $instance->features = $row['features'];
                array_push($tables, $instance);
            }
        }
        return $tables;
    }

}
