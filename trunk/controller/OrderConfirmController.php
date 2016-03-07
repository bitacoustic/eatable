<?php

include_once('model/User.php');
include_once('model/Restaurant.php');
include_once('model/Reservation.php');
include_once('model/MenuItem.php');

class OrderConfirmController {

	private $user;
        private $restaurant;
	private $reservation;
        private $menuitems = array();
        
	public function __construct() {
		if (isset($_COOKIE['eatable-user-session'])) {
                    $this->user = User::getUserBySessionId($_COOKIE['eatable-user-session']);
                }
                $this->restaurant = Restaurant::getRestaurantById($_GET["restaurant-id"]);
                
                //Construct a new reservation based on selected information
                $this->reservation = new Reservation("", $this->user->id, $this->restaurant->id,
                $_GET["selectedDate"], $_GET["selectedTime"],
                $_GET["partySize"], $_GET["table-id"], 
                $_GET["specialInstruction"], $_GET["reservation-status"],$_GET["menuitem"]);
            
                //Insert a new reservation into Reservation Table
                $this->retval = $this->reservation->placeReservation();
                
                $menuitem_str  = $_GET["menuitem"];
                $items = explode(";", $menuitem_str);
                foreach ($items as $item) {
                    $dishs = explode(",", $item);
                if ( isset( $dishs[0], $dishs[1])){
                        $item_id = $dishs[0];
                        $quantity = $dishs[1];
                        $menuitem = MenuItem::getMenuItemById($item_id);
                        $menuitem->quantity = $quantity;
                        array_push($this->menuitems, $menuitem);
                    }
                }
                
                /*$menu1 = new MenuItem("0001","Chicken Satay","Chicken Marinate Stew","$6.95",1);
                array_push($this->menuitems, $menu1);
                $menu2 = new MenuItem("0002","Crispy Roll","Crispy vegetariam rolls","$6.95",1);
                array_push($this->menuitems, $menu2);
                $menu3 = new MenuItem("0003","Fresh Spring Roll","Fresh vegetariam rolls","$5.95",2);
                array_push($this->menuitems, $menu3);
                $menu4 = new MenuItem("0004","Pad Thai","Stirred fried small rice noodle with tamarine sauce and dried chilly","$8.95",4);
                array_push($this->menuitems, $menu4);
                $menu5 = new MenuItem("0005","Pad See Ew","Stirred fried big rice noodle with soy sauce and egg","$8.95",3);
                array_push($this->menuitems, $menu5);*/
	}
	
	public function invoke() {
		// Load the orderConfirm page.
		include 'view/page/order_confirm_page.php';
	}

}
