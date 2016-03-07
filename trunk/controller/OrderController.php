<?php

include_once('model/User.php');
include_once('model/Restaurant.php');
include_once('model/Reservation.php');
include_once('model/Order.php');
include_once('model/OrderItem.php');
include_once('model/MenuItem.php');

class OrderController {

    private $user;
    private $reservation;
    private $reservationId;
	private $order;
	private $instruction ="";
        private $orderitems = array();
        private $displayOrderitems = array();
    private $menuitems = array();

    public function __construct() {
        if (isset($_COOKIE['eatable-user-session'])) {
            $this->user = User::getUserBySessionId($_COOKIE['eatable-user-session']);
        }
		if(isset($_GET['restaurant-id'])) {
                    $this->restaurant = Restaurant::getRestaurantById($_GET["restaurant-id"]);
		}
		if(isset($_GET['reservation-id'])) {
                    $this->reservationId = $_GET['reservation-id'];
		}
    }

    public function invoke() {
		// Retrieve the reservation parameters.
        $partySize = intval($_GET['partySize']);
        $dateTime = DateTime::createFromFormat('m-d-Y', $_GET['selectedDate']);
        $date = $dateTime->format('Y-m-d');
        $d = DateTime::createFromFormat('H:i A ', $_GET['selectedTime']);
        $time = $d->format('H:i:s');
		// Create a reservation object.
        $this->reservation = Reservation::queryReservation($this->restaurant->id, $date, $time, $partySize);
		
		// Check for special instructions.
        if($this->reservation->tableId != NIL){
            if(isset($_GET['specialinstruction'])){
                $this->instruction = $_GET['specialinstruction'];
            }
            
            //Construct a new reservation based on selected information
            $this->reservation = new Reservation("", $this->user->id, $this->restaurant->id,
            									$this->reservation->date, $this->reservation->time,
												$partySize, $this->reservation->tableId, 
												$this->instruction, $this->reservation->status,"");
            $this->reservation->placeReservation();
        }
        
            // Retrieve the menu items for the specified restaurnt.
            $this->menuitems = MenuItem::getMenuItemsByRestaurant($this->restaurant->id);
        
            // Create a new order object.
//		$this->order = new Order(NIL, $this->reservation->id);
		$this->order = Order::createOrder($this->reservation->id);
		
        // Load the order page
        include 'view/page/order_page.php';
    }
	
	public function submitOrder() {
            
                // Create a new order object.
                //$this->order = Order::createOrder($_GET['reservation-id']);
                //get Order by reservationId
		$this->order = Order::getOrderByReservationId($this->reservationId);

		// Retrieve GET data containing order items.
		// create an array of orderItems from the GET data.
		// Test Example.
                $menuitem_str = '';
                if(isset($_GET['menuitem'])){
                    $menuitem_str = $_GET['menuitem'];
                }
                $items = explode(";", $menuitem_str);
                foreach ($items as $item) {
                    $dishs = explode(",", $item);
                    if ( isset( $dishs[0], $dishs[1])){
                        $item_id = $dishs[0];
                        $quantity = $dishs[1];
                        $orderitem = new OrderItem(NIL, $this->order->id, $item_id, $quantity);
                        //$menuitem = MenuItem::getMenuItemById($item_id);
                        //$menuitem->quantity = $quantity;
                        array_push($this->orderitems, $orderitem);
                    }
                }
                
                /*$this->orderItems = array(new OrderItem(NIL, $this->order->reservationId, 6, 2),
								  new OrderItem(NIL, $this->order->reservationId, 7, 3),
			   		   			  new OrderItem(NIL, $this->order->reservationId, 8, 1));*/
		
		// Store the order in the db.
		foreach ($this->orderitems as $item) {
			OrderItem::createOrderItem($item->orderId, $item->menuItemId, $item->quantity);
		}
                $this->restaurant = Restaurant::getRestaurantById($_GET["restaurant-id"]);
		$this->reservation = Reservation::getReservationById($this->reservationId);
                $this->displayOrderitems = OrderItem::getOrderItemsByOrderId($this->order->id);
		// Load order confirmation.
		include 'view/page/order_confirm_page.php';
	}
	
	public function cancelOrder() {
            
                $this->restaurant = Restaurant::getRestaurantById($_GET["restaurant-id"]);
		$this->reservation = Reservation::getReservationById($this->reservationId);
		//$this->order = Order::cancelOrderByReservationId($this->reservationId);
		// Load order confirmation.
		include 'view/page/reservation_confirm_page.php';
	}
}
