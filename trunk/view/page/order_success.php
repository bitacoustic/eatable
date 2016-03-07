<?php

echo "Your order has been submitted. <br>";
echo "Order ID: " . $this->order->id . "<br>";
echo "Reservation ID: " . $this->order->reservationId . "<br>";

echo "<h3>Order Items</h3>";
foreach ($this->orderitems as $item) {
	echo "Order ID: $item->orderId<br>";
	echo "Menu Item ID: $item->menuItemId<br>";
	echo "Quantity: $item->quantity<br><br>";
}