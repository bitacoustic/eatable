<?php

include_once 'model/Model.php';

/**
 * Order class
 */
class Order extends Model {

    private $id;
    private $reservationId;

    /**
     * Construct an order item object
     * 
     * @param integer $id
     * @param integer $reservation_id
     */
    public function __construct($id = NIL, $reservationId = NIL) {
        $this->id = $id;
        $this->reservationId = $reservationId;
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

    public static function createOrder($reservationId) {
        // Creates a new order entry for a specfied reservation SQL.	
        $sql = "INSERT INTO Orders (reservation_id) VALUES('$reservationId')";
        $orderId = parent::insertDatabase($sql);

        return new self(NIL, $orderId);
    }

    public static function cancelOrder($reservationId) {
        // Creates a new order entry for a specfied reservation SQL.	
        $sql = "DELETE FROM Orders WHERE reservation_id = '$reservationId')";
        $this->queryDatabase($sql);
    }

    public function addItemToOrder($menu_item_id, $quantity) {
        $sql = "INSERT INTO OrderItems (order_id, menu_item_id, quantity) VALUES "
                . "('$this->id', '$menu_item_id', '$quantity')";

        if ($this->insertDatabase($sql)) {
            return True;
        } else {
            return False;
        }
    }

    public static function getOrderItems($reservationId) {
        $sql = "SELECT * FROM OrderItems WHERE reservation_id = $reservationId";
        $results = $this->queryDatabase($sql);

        if ($results && $results->num_rows > 0) {
            $orderItems = array();
            while ($row = $results->fetch_assoc()) {
                $orderItem = new OrderItem($row ['id'], $row['order_id'], $row ['menu_item_id'], $row ['quantity']);
                array_push($orderItems, $orderItem);
            }
        }

        return $orderItems;
    }

    public static function getOrderByReservationId($reservationId) {
        // Build the sql query.
        $sql = "SELECT * FROM Orders WHERE reservation_id = $reservationId";
        // Query the database.
        $results = parent::queryDatabase($sql);

        // Parse the results.
        if ($results->num_rows == 1) {
            while ($row = $results->fetch_assoc()) {
                $instance = new self();
                $instance->id = $row['id'];
                $instance->reservationId = $row['reservation_id'];
            }

            // Return the user object.
            return $instance;
        }
    }

}
