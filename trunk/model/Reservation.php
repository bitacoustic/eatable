<?php

include_once 'model/Model.php';
include_once 'model/Restaurant.php';
include_once 'model/Table.php';

/*
 * Reservation class holds a reservation at a restaurant, placed by a general
 * user.
 */

class Reservation extends Model {

    // constants; can be called statically, e.g. Reservation::STATUS_PENDING
    const STATUS_UNPLACED = 1;
    const STATUS_PENDING = 2;
    const STATUS_CHECKEDIN = 3;
    const STATUS_CANCELLED = 4;
    const STATUS_NOSHOW = 5;
    
    const UNREGISTERED_USER_ID = 100;

    // member variables
    private $id;
    private $userId;
    private $restaurantId;
    private $date;
    private $time;
    private $partySize;
    private $tableId;
    private $specialInstructions;
    private $status;
    private $mealOrder;

    public function __construct($id = NIL, $userId = NIL, $restaurantId = NIL, $date = NIL, $time = NIL, $partySize = NIL, $tableId = NIL, $specialInstructions = NIL, $status = NIL, $mealOrder = NIL) {
        $this->id = $id;
        $this->userId = $userId;
        $this->restaurantId = $restaurantId;
        $this->date = $date;
        $this->time = $time;
        $this->partySize = $partySize;
        $this->tableId = $tableId;
        $this->specialInstructions = $specialInstructions;
        $this->status = $status;
        $this->mealOrder = $mealOrder;
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

    /**
     * Places this unplaced reservation by writing it to the database. If
     * successful, reservation will show as Reservation::STATUS_PENDING,
     * otherwise it remains Reservation::STATUS_UNPLACED.
     * 
     * Precondition: the parameter is an unplaced reservation
     */
        public function placeReservation() {
        // Create a database connection.
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

        // Check the connection.
        if (!$conn) {
            $retval = "There was an error connecting to the database";
        } else {
            $conn->autocommit(false);

            $this->specialInstructions = $conn->escape_string($this->specialInstructions);
            $status = Reservation::STATUS_PENDING;

            // Add the reservation to the db
            $sql = "INSERT INTO Reservations(`user_id`,`restaurant_id`,`date`,`time`,`table_id`,`party_size`,`special_instructions`,`status_id`,`meal_order`) 
                    VALUES({$this->userId},{$this->restaurantId},
                    '{$this->date}','{$this->time}',
                    {$this->tableId},{$this->partySize},
                    '{$this->specialInstructions}',{$status},
                    '{$this->mealOrder}')";
            $result = $conn->query($sql);


            // If both queries execute, commit the change.  Else revert.
            if ($result) {
                $this->id = $conn->insert_id;
                $this->status = Reservation::STATUS_PENDING;
                $conn->commit();
                $retval = "The new record was added to the database.";
            } else {
                $conn->rollback();
                $retval = "The new record could not be added to the database.";
            }

            $conn->autocommit(true);
            $conn->close();
        }

        return $retval ;
    }

    /**
     * Removes reservation with specified ID from the database.
     */
    public static function removeById($id) {
        $sql = "DELETE FROM Reservations WHERE id = " . $id;
        parent::insertDatabase($sql);
        return True;
    }

    /**
     * Returns the reservation with the specified reservation ID.
     * 
     * Precondition: reservation IDs are unique
     */
    public static function getReservationById($reservationId) {
        $reservation = NIL;
        $sql = "SELECT * FROM Reservations WHERE id = " . $reservationId;
        $results = parent::queryDatabase($sql);
        if ($results->num_rows == 1) {
            $row = $results->fetch_assoc();
            $reservation = new self($row['id']);
            $reservation->userId = $row['user_id'];
            $reservation->restaurantId = $row['restaurant_id'];
            $reservation->date = $row['date'];
            $reservation->time = $row['time'];
            $reservation->partySize = $row['party_size'];
            $reservation->tableId = $row['table_id'];
            $reservation->specialInstructions = $row['special_instructions'];
            $reservation->status = $row['status_id'];
            $reservation->mealOrder = $row['meal_order'];
        }
        return $reservation;
    }

    public static function getReservationsByUserId($userId) {
        $reservations = NIL;
        $sql = "SELECT * FROM Reservations WHERE user_id = " . $userId
                . " ORDER BY date, time";
        $results = parent::queryDatabase($sql);
        if ($results->num_rows > 0) {
            $reservations = array();
            while ($row = $results->fetch_assoc()) {
                $instance = new self($row['id']);
                $instance->userId = $row['user_id'];
                $instance->restaurantId = $row['restaurant_id'];
                $instance->date = $row['date'];
                $instance->time = $row['time'];
                $instance->partySize = $row['party_size'];
                $instance->tableId = $row['table_id'];
                $instance->specialInstructions = $row['special_instructions'];
                $instance->status = $row['status_id'];
                $instance->mealOrder = $row['meal_order'];
                array_push($reservations, $instance);
            }
        }
        return $reservations;
    }

    /**
     * Returns an array of reservations for the specified restaurant, on the
     * specified day.
     * 
     * Precondition: restaurantId is valid, date is valid and formatted correctly
     */
    public static function getReservations($restaurantId, $date) {
        $reservations = NIL;
        $sql = "SELECT * FROM Reservations WHERE restaurant_id = '" . $restaurantId .
                "' AND date = '" . $date . "' ORDER BY time ASC";
        $results = parent::queryDatabase($sql);
        if ($results->num_rows > 0) {
            $reservations = array();
            while ($row = $results->fetch_assoc()) {
                $instance = new self($row['id']);
                $instance->userId = $row['user_id'];
                $instance->restaurantId = $row['restaurant_id'];
                $instance->date = $row['date'];
                $instance->time = $row['time'];
                $instance->partySize = $row['party_size'];
                $instance->tableId = $row['table_id'];
                $instance->specialInstructions = $row['special_instructions'];
                $instance->status = $row['status_id'];
                $instance->mealOrder = $row['meal_order'];
                array_push($reservations, $instance);
            }
        }
        return $reservations;
    }

    /**
     * Returns a list of all reservations in the database, with optional sort
     * and filter parameters.
     */
    public static function getAllReservations($sort = NIL, $filterProperty = NIL, $filterValue = NIL, $filterFlagNumber = NIL) {
        $allReservations = NIL;
        $sql = "SELECT * FROM Reservations";
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
            $allReservations = array();
            while ($row = $results->fetch_assoc()) {
                $instance = new self($row['id']);
                $instance->userId = $row['user_id'];
                $instance->restaurantId = $row['restaurant_id'];
                $instance->date = $row['date'];
                $instance->time = $row['time'];
                $instance->partySize = $row['party_size'];
                $instance->tableId = $row['table_id'];
                $instance->specialInstructions = $row['special_instructions'];
                $instance->status = $row['status_id'];
                $instance->mealOrder = $row['meal_order'];
                array_push($allReservations, $instance);
            }
        }
        return $allReservations;
    }

    /**
     * Update the database record referenced by the given reservation ID by assigning
     * a value to the given property.
     * 
     * This update method is for numerical values. To update a property holding
     * a string, use Reservation::updateString().
     */
    public static function updateNumber($reservationId, $property, $value) {
        if (property_exists(new Reservation(), $property)) {
            $sql = "UPDATE Reservations SET " . $property . " = " . $value
                    . " WHERE id = " . $reservationId;
            return parent::insertDatabase($sql);
        } else {
            return False;
        }
    }

    /**
     * Update the database record referenced by the given reservation ID by assigning
     * a value to the given property.
     * 
     * This update method is for strings (the generated SQL statement encloses
     * the value in single quotes). To update a field holding a numerical 
     * value, use Reservation::updateNumber().
     */
    public static function updateString($reservationId, $property, $value) {
        if (property_exists(new Reservation(), $property)) {
            $sql = "UPDATE Reservations SET " . $property . " = '" . $value
                    . "' WHERE id = " . $reservationId;
            return parent::insertDatabase($sql);
        } else {
            return False;
        }
    }

    public function updateStatus($status) {
        $sql = "UPDATE Reservations SET status_id = " . $status
                . " WHERE id = " . $this->id;
        return parent::queryDatabase($sql);
    }

    /**
     * Determines if a reservation is available for the specified party size,
     * at the specified restaurant on the specified date and time.
     * If available, returns the (as yet unplaced) reservation; otherwise,
     * returns NIL.
     * 
     * Precondition: parameters are valid and formatted correctly
     */
    public static function queryReservation($restaurantId, $date, $time, $partySize, $debug = NIL) {
        if ($debug === NIL) {
//            unset($debug);
            $debug = false;
        }
        // Calculate the start and end times of the reservation in UNIX time
        $startTimestamp = strtotime($date . " " . $time);
        $endTimestamp = strtotime("+2 hours", $startTimestamp);
        if ($debug) {
            echo "<p><strong>startTimestamp</strong>: " . date("l, F dS, Y g:i A", $startTimestamp) . "</p>\n";
            echo "<p><strong>endTimestamp</strong>: " . date("l, F dS, Y g:i A", $endTimestamp) . "</p>";
        }


        // STEP 1. If the start time of the proposed reservation occurs while the
        // restaurant is closed, or the end of the reservation period falls
        // more than 30 minutes past closing time, return NIL
        // Get reservation's day of the week as integer
        $reservationDayOfWeek = intval(date("w", $startTimestamp));
        if ($debug) {
            echo "<hr/><p>STEP 1. If the start time of the proposed reservation occurs while the
                restaurant is closed, or the end of the reservation period falls
                more than 30 minutes past closing time, return NIL</p>";
            echo "<p><strong>reservationDayOfWeek</strong>: {$reservationDayOfWeek}</p>";
        }

        // Compare to restaurant's hours for that day
        $sql = "SELECT start_time, end_time FROM RestaurantHours WHERE restaurant_id = "
                . $restaurantId . " AND day_of_week = " . $reservationDayOfWeek
                . " ORDER BY start_time ASC";
        if ($debug) {
            echo "<p><strong>Compare to restaurant's hours for that day</strong>: {$sql}</p>";
        }
        $hoursOnDay = parent::queryDatabase($sql);

        // Check for reservation time out of range
        $rowsRemaining = $hoursOnDay->num_rows;
        if ($rowsRemaining == 0) {
            return NIL;
        }
        $lastEnd = 0;
        while ($hoursRange = $hoursOnDay->fetch_assoc()) {
            $rowsRemaining--;
            $hoursStart = strtotime($date . " " . $hoursRange['start_time']);
            $hoursEnd = strtotime($date . " " . $hoursRange['end_time']);
            $hoursEnd = strtotime("+30 minutes", $hoursEnd);
            if ($debug) {
                echo "<p><strong>rowsRemaining</strong>: {$rowsRemaining}</p>";
                echo "<p><strong>hoursStart</strong>: " . date("l, F dS, Y g:i A", $hoursStart) . "</p>";
                echo "<p><strong>hoursEnd</strong>: " . date("l, F dS, Y g:i A", $hoursEnd) . "</p>";
            }

            // if start time occurs while restaurant is closed
            if ($lastEnd < $startTimestamp && $hoursStart > $startTimestamp) {
                if ($debug) {
                    echo "<p><strong>start time occurs while restaurant is closed</strong></p>";
                }
                return NIL;
            }
            // else if end of reservation falls more than 30 minutes past close
            elseif ($endTimestamp > $hoursEnd && $rowsRemaining == 0) {
                if ($debug) {
                    echo "<p><strong>end of reservation falls more than 30 minutes past close</strong></p>";
                }
                return NIL;
            }
            $lastEnd = $hoursEnd;
        }

        // Get all of the day's reservations, and tables at restaurant
        $reservations = Reservation::getReservations($restaurantId, $date);
        $tables = Table::getTablesByRestaurant($restaurantId);
        if ($debug) {
            echo "<p><strong>The day's reservations at restaurant {$restaurantId}</strong>:<br><pre>";
            echo var_dump($reservations);
            echo "</pre></p>";
            echo "<p><strong>All restaurant's tables</strong>:<br><pre>";
            echo var_dump($tables);
            echo "</pre></p>";
        }

        if (count($tables) < 1) {
            if ($debug) {
                echo "<p><strong>no tables available after step 1</strong></p>";
            }
            return NIL;
        }

        // STEP 2. Remove those tables not able to accomodate party size.
        if ($debug) {
            echo "<hr/><p>STEP 2. Remove those tables not able to accomodate party size.</p>";
        }
        if ($tables) {
            $exclude = array();
            foreach ($tables as $index => $table) {
                if ($partySize > $table->seatingCapacity) {
                    array_push($exclude, $index);
                    if ($debug) {
                        echo "<p>Excluding table {$table->tableNumber} (index {$index}).</p>";
                    }
                }
            }
            Reservation::excludeItemsByIndex($exclude, $tables);
        } else {
            return NIL;
        }

        if ($debug) {
            echo "<p><strong>Tables after step 2</strong>:<br><pre>";
            echo var_dump($tables);
            echo "</pre></p>";
        }

        if (count($tables) < 1) {
            if ($debug) {
                echo "<p><strong>no tables available after step 2</strong></p>";
            }
            return NIL;
        }

        // STEP 3. Exclude those tables that already have a reservation 
        // whose period at least partially overlaps the requested reservation
        // period. If the list is now empty, finish and return nothing. 
        if ($debug) {
            echo "<hr/><p>STEP 3. Exclude those tables that already have a reservation 
                whose period at least partially overlaps the requested reservation
                period. If the list is now empty, finish and return nothing.</p>";
        }

        $exclude = array();
        if ($reservations) {
            foreach ($reservations as $reservation) {
                $thisStart = strtotime($reservation->date . " " . $reservation->time);
                $thisEnd = strtotime("+2 hours", $thisStart);
                if ($debug) {
                    echo "<p>Reservation at table UID {$reservation->tableId} begins "
                    . date("l, F dS, Y g:i A", $thisStart) . " and ends "
                    . date("l, F dS, Y g:i A", $thisEnd) . "</p>\n";
                }
                if (($thisStart <= $startTimestamp && $thisEnd > $startTimestamp) || ($thisStart > $startTimestamp && $thisStart <= $endTimestamp)) {
                    foreach ($tables as $index => $table) {
                        if ($table->id === $reservation->tableId) {
                            array_push($exclude, $index);
                            if ($debug) {
                                echo "<p>Excluding table number {$table->tableNumber} (UID {$reservation->tableId}, index {$index}).</p>";
                            }
                        }
                    }
                }
            }
        }

        Reservation::excludeItemsByIndex($exclude, $tables);

        if ($debug) {
            echo "<p><strong>Tables after step 3</strong>:<br><pre>";
            echo var_dump($tables);
            echo "</pre></p>";
        }
        if (count($tables) < 1) {
            return NIL;
        }

        // STEP 4. From the remaining tables, choose and return a reservation on
        // a unique table of least capacity that accommodates the party during
        // the requested reservation period.
        // NOTE: From this point we should not be returning NIL.
        if ($debug) {
            echo "<hr/><p>STEP 4. From the remaining tables, choose and return a reservation on
            a unique table of least capacity that accommodates the party during
            the requested reservation period.
            NOTE: From this point we should not be returning NIL.</p>";
        }

        $minCapacity = PHP_INT_MAX;
        foreach ($tables as $table) {
            if ($table->seating_capacity < $minCapacity) {
                $minCapacity = $table->seatingCapacity;
            }
        }
        $targetIndex = -1;
        foreach ($tables as $index => $table) {
            if ($table->seatingCapacity == $minCapacity) {
                $targetIndex = $index;
                break;
            }
        }

        // Reservation is assigned to this table.
        $targetTable = $tables[$targetIndex];

        // Build and return the reservation
        // NOTE: This does not place the reservation
        $retval = new self();
        $retval->restaurantId = $restaurantId;
        $retval->date = $date;
        $retval->time = $time;
        $retval->partySize = $partySize;
        $retval->tableId = $targetTable->id;
        // let specialInstructions remain NIL
        $retval->status = Reservation::STATUS_UNPLACED;
        // let mealOrder remain NIL

        return $retval;
    }

    /* HELPER METHODS */

    /**
     * Exclude items in $items with keys in the $exclude array.
     * @param array $exclude - an array of items from which to exclude certain elements
     * @param reference to array of numbers $items
     *        - keys for elements to remove from $exclude array
     */
    private function excludeItemsByIndex($exclude, &$items) {
        while (count($exclude) > 0) {
            // Note the keys (index numbers) do not change after an unset
            // operation.
            $indexToRemove = array_pop($exclude);
            unset($items[$indexToRemove]);
        }
    }

    // Old version -- doesn't remove the last element from $exclude array
//    private function excludeItemsByIndex($exclude, &$items) {
//        while ($indexToRemove = array_pop($exclude)) {
//            foreach ($items as $index => &$item) { //key,val pair with val as reference
//                if ($index == $indexToRemove) {
//                    // remove item in place; indices are unchanged
//                    unset($items[$index]);
//                }
//                unset($item); // free mem associated with item
//            }
//        }
//    }
}
