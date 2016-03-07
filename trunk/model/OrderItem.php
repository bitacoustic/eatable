<?php

include_once 'model/Model.php';

/**
 * Order item class holds information about an item of a specific order.
 */
class OrderItem extends Model {

    private $id;
    private $orderId;
    private $menuItemId;
    private $quantity;
    private $specialInstructions;
    private $name;
    private $price;

    /**
     * Construct an order item object
     * 
     * @param integer $id
     * @param string $name
     * @param string $addressStreet
     * @param string $addressCity
     */
    public function __construct($id = NIL, $orderId = NIL, $menuItemId = NIL, $quantity = NIL, $specialInstructions = NIL) {
        $this->id = $id;
        $this->orderId = $orderId;
        $this->menuItemId = $menuItemId;
        $this->quantity = $quantity;
        $this->specialInstructions = $specialInstructions;
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

    public static function createOrderItem($orderId, $menuItemId, $quantity) {
        $sql = "INSERT INTO OrderItems (order_id, menu_item_id, quantity) VALUES ($orderId, $menuItemId, $quantity)";
        if (parent::insertDatabase($sql)) {
            return True;
        } else {
            return False;
        }
    }

    /**
     * Retrieves all order items associated with a unique order ID.
     * @param integer $orderId - unique ID or a order
     * @return array of OrderItems
     */
    public static function getOrderItemsByOrderId($orderId) {
        $orderItems = NIL;
        $sql = "SELECT * FROM OrderItems R, MenuItems M WHERE "
                . " R.menu_item_id = M.id "
                . " and order_id = " . $orderId;
        $results = parent::queryDatabase($sql);
        if ($results->num_rows > 0) {
            $orderItems = array();
            while ($row = $results->fetch_assoc()) {
                $instance = new self($row['id']);
                $instance->orderId = $row['order_id'];
                $instance->menuItemId = $row['menu_item_id'];
                $instance->name = $row['name'];
                $instance->description = $row['special_instructions'];
                $instance->price = $row['price'];
                $instance->id = $row['id'];
                $instance->quantity = $row['quantity'];
                array_push($orderItems, $instance);
            }
        }
        return $orderItems;
    }

}
