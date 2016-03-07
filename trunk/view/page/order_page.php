<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Eatable</title>

        <!-- Bootstrap Core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <!-- Custom CSS -->
        <link href="css/modern-business.css" rel="stylesheet">
        <link href="css/eatable.css" rel="stylesheet">
    </head>

    <body>
        <!-- Navigation bar -->
        <?php include_once('view/module/navigation.php') ?>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $this->restaurant->name; ?>
                        <small><?php echo $this->restaurant->cuisineType; ?></small>
                    </h1>
                </div>
            </div>

            <!-- Contact Form -->
            <!-- In order to set the email address and subject line for the contact form go to the bin/contact_me.php file. -->
            <div class="well">
                <div class="row">
                    <div class="col-md-12">
                        <h2 style="margin-top: -5px" class="page-header"> <?php echo $this->restaurant->name; ?> <small><?php echo $this->restaurant->addressStreet . ", " . $this->restaurant->addressCity . ", " . $this->restaurant->addressState . ", " . $this->restaurant->addressPostalCode; ?></small></h2> 
                        <div  class="control-group form-group form-inline">
                            <div class="controls">
                                <div class="col-md-3"><label>Name: </label> <?php echo $this->user->fname . " " . $this->user->lname ?></div>
                                <div class="col-md-4"><label>Phone Number: </label> <?php echo $this->user->phone_number ?></div>
                                <div class="col-md-5"><label>Email Address: </label> <?php echo $this->user->email ?></div>
                            </div>
                        </div>
                        <div class="control-group form-group form-inline">
                            <div class="col-md-3"><label>Party Size:</label> <?php echo $this->reservation->partySize ?></div>
                            
                            <!--format date -->
                            <?php $dateTime = DateTime::createFromFormat('Y-m-d', $this->reservation->date);
                            $date = $dateTime->format('m-d-Y');?>
                            <div class="col-md-4"><label>Date:</label> <?php echo $date ?></div>
                                
                            <!--format time -->
                            <?php $d = DateTime::createFromFormat('H:i:s', $this->reservation->time);
                            $time = $d->format('H:i A');?>
                            <div class="col-md-5"><label>Time:</label> <?php echo $time ?></div>
                        </div>
                        <br>
                        <div class="control-group form-group">
                            <div class="col-md-12">
                                <label>Special Instruction: </label>
                                <?php echo $this->reservation->specialInstructions ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><strong>Menu</strong></h3>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-condensed" width="100%" id="menuItemID">
                                    <thead>
                                        <tr>
                                            <td width="20%"><strong>Item</strong></td>
                                            <td width="60%"><strong>Description</strong></td>
                                            <td width="20%"><strong>Price</strong></td>
                                            <td class="text-right"><strong>Quantity</strong></td>
                                            <td class="text-right" style="display: none"><strong>ID</strong></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($this->menuitems != NIL) {
											// Item counter for differentiating item GET ids
											$cnt = 1;
                                            foreach ($this->menuitems as $item) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $item->name ?></td>
                                                    <td><?php echo $item->description ?></td>
                                                    <td><?php echo $item->price ?></td>
                                                    <td class="text-right">
                                                        <div class="form-group">
                                                            <select id="quantitySelect" class="form-control" onChange="calculatePrice('<?php echo $cnt ?>')">
                                                                <option>0</option>
                                                                <option>1</option>
                                                                <option>2</option>
                                                                <option>3</option>
                                                                <option>4</option>
                                                                <option>5</option>
                                                                <option>6</option>
                                                                <option>7</option>
                                                                <option>8</option>
                                                                <option>9</option>
                                                            </select> 
                                                        </div>
                                                    </td>
                                                    <td style="display: none"><?php echo $item->id ?></td>
                                                </tr>
                                            <?php }
                                        }
                                        ?>
                                        <tr>
                                            <td class="thick-line"></td>
                                            <td class="thick-line"></td>
                                            <td class="thick-line text-center"><strong>Subtotal</strong></td>
                                            <td class="thick-line text-right">$<span id="subtotal">0.00</span></td>
                                        </tr>
                                        <tr>
                                            <td class="no-line"></td>
                                            <td class="no-line"></td>
                                            <td class="no-line text-center"><strong>Tax</strong></td>
                                            <td class="no-line text-right">$<span id="tax">0.00</span></td>
                                        </tr>
                                        <tr>
                                            <td class="no-line"></td>
                                            <td class="no-line"></td>
                                            <td class="no-line text-center"><strong>Total</strong></td>
                                            <td class="no-line text-right">$<span id="total">0.00</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			<!-- Submit order button -->
            <div style="text-align: right" class="control-group form-group form-inline">
                <form action="index.php" method="GET">
                    <button class="btn btn-primary" style="margin-right: 10px" type="submit">Make Reservation Without Meal Order</button>
			<input type="hidden"  name="controller" value="Order">
			<input type="hidden" name="action" value="cancelOrder">
			<input type="hidden" name="reservation-id" value="<?php echo $this->reservation->id ?>">
                        <input type="hidden" name="restaurant-id" value="<?php echo $this->restaurant->id ?>">
                   <a class="btn btn-primary" style="text-align: right" data-toggle="modal" data-target="#confirmOrderModal">Place Meal Order</a>
		</form>
                
            </div>
        </div>

		<!-- Confirm Order Modal -->
		<div class="modal fade" id="confirmOrderModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">Confirm Order</h4>
					</div>
					<div class="modal-body">
						<!-- Relist chosen order items here -->
						<div class="table-responsive">
                                <table class="table table-condensed" width="100%" id="menuItemID">
                                    <thead>
                                        <tr>
                                            <td width="20%"><strong>Item</strong></td>
                                            <td width="60%"><strong>Description</strong></td>
                                            <td width="20%"><strong>Price</strong></td>
                                            <td class="text-right"><strong>Quantity</strong></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($this->menuitems != NIL) {
											// Item counter for differentiating item GET ids
											$cnt = 1;
                                            foreach ($this->menuitems as $item) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $item->name ?></td>
                                                    <td><?php echo $item->description ?></td>
                                                    <td><?php echo $item->price ?></td>
                                                    <td id="itemId<?php echo $cnt; $cnt += 1;?>"></td>
                                                </tr>
                                            <?php }
                                            
                                        }
                                        ?>
                                                <tr>
                                            <td class="thick-line"></td>
                                            <td class="thick-line"></td>
                                            <td class="thick-line text-center"><strong>Subtotal</strong></td>
                                            <td class="thick-line text-right">$<span id="subtotal_confirm">0.00</span></td>
                                        </tr>
                                        <tr>
                                            <td class="no-line"></td>
                                            <td class="no-line"></td>
                                            <td class="no-line text-center"><strong>Tax</strong></td>
                                            <td class="no-line text-right">$<span id="tax_confirm">0.00</span></td>
                                        </tr>
                                        <tr>
                                            <td class="no-line"></td>
                                            <td class="no-line"></td>
                                            <td class="no-line text-center"><strong>Total</strong></td>
                                            <td class="no-line text-right">$<span id="total_confirm">0.00</span></td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </div>
					</div>
					<div class="modal-footer">
						<form action="index.php" method="GET" id="confirmForm">
							<div style="text-align: center; margin-bottom: 20px">
								<a class="btn btn-primary" href="javascript: submitOrder()">Confirm Meal Order</a>
								<input type="hidden" id='controllerID' name="controller" value="Order">
								<input type="hidden" name="action" value="submitOrder">
                                                                <input type="hidden" name="menuitem" id="orderItemID">
								<input type="hidden" name="reservation-id" value="<?php echo $this->reservation->id ?>">
                                                                <input type="hidden" name="restaurant-id" value="<?php echo $this->restaurant->id ?>">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

        <!-- Footer -->
		<?php include_once('view/module/footer.php'); ?>

        <!-- jQuery Version 1.11.0 -->
        <script src="js/jquery-1.11.0.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="js/bootstrap.min.js"></script>

        <script>
            function calculatePrice(cnt) {
                count =1;
                var subtotal = 0.00;
                var table = $("#menuItemID tbody");
                table.find('tr').each(function (i, el) {
                    var $tds = $(this).find('td');
                    price = $tds.eq(2).text();
                    price = price.replace('$', '');
                    var $quantity = $(this).find('#quantitySelect');
                    $tds.eq(5).text($quantity.val());
                    $("#itemId"+count).text($quantity.val());
                    if($.isNumeric($quantity.val()) && $.isNumeric(price)){
                        subtotal = subtotal + ($quantity.val() * price);
                    }
                    count +=1;
                });
                
                $("#subtotal").text(subtotal.toFixed(2));
                $("#tax").text((subtotal * 0.0875).toFixed(2));
                $("#total").text((subtotal * 1.0875).toFixed(2));
                
                $("#subtotal_confirm").text(subtotal.toFixed(2));
                $("#tax_confirm").text((subtotal * 0.0875).toFixed(2));
                $("#total_confirm").text((subtotal * 1.0875).toFixed(2));
            }

            function submitOrder()
            {   
                var table = $("#menuItemID tbody");
                var menu_order = '';
                table.find('tr').each(function (i, el) {
                    var $tds = $(this).find('td');
                    id = $tds.eq(4).text();
                    quantity = $(this).find('#quantitySelect').val();
                    if(quantity > 0){
                        menu_order += id+','+quantity+';';
                    }
                });
                $('#orderItemID').val(menu_order);
                document.getElementById("confirmForm").submit();
            }
        </script>
