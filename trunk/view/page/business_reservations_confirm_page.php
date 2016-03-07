<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>eatable</title>
        <!-- Bootstrap Core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="css/modern-business.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">



    </head>



    <body>
        <!-- Navigation bar -->
        <?php include_once('view/module/navigation.php') ?>

        <div class="well" style="margin-top: 10px">
            <div class="btn-group btn-group-justified" role="group" aria-label="...">
                <div class="btn-group" role="group">
                    <!-- <button type="button" class="btn btn-default">Close</button>-->
                    <a class="btn btn-default " href="index.php?controller=Business&action=invoke">Close</a>
                    <!--a class="btn-group btn-block" href="index.php?controller=Admin&action=invoke">Admin Login</a-->
                </div>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-default">Modify reservation</button>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-default">Print</button>
                </div>
            </div>
        </div> 
        <hr>
        <div class="container">
            <div class="row">
                <div class="col-xs-12"> 
                    <div class="invoice-title">
                        <h2>Reservation Confirmation</h2><h3 class="pull-right">Order # 12345</h3>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-xs-6">
                            <address>
                                <strong>Billed To:</strong><br>
                                John Smith<br>
                                1234 Main<br>
                                Apt. 4B<br>
                                Springfield, ST 54321
                            </address>
                        </div>
                        <div class="col-xs-6 text-right">
                            <address>
                                <strong>Shipped To:</strong><br>
                                Jane Smith<br>
                                1234 Main<br>
                                Apt. 4B<br>
                                Springfield, ST 54321
                            </address>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <address>
                                <strong>Payment Method:</strong><br>
                                Visa ending **** 4242<br>
                                jsmith@email.com
                            </address>
                        </div>
                        <div class="col-xs-6 text-right">
                            <address>
                                <strong>Order Date:</strong><br>
                                November 13, 2015<br><br>
                            </address>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><strong>Order summary</strong></h3>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-condensed">
                                    <thead>
                                        <tr>
                                            <td><strong>Item</strong></td>
                                            <td class="text-center"><strong>Price</strong></td>
                                            <td class="text-center"><strong>Quantity</strong></td>
                                            <td class="text-right"><strong>Totals</strong></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- foreach ($order->lineItems as $line) or some such thing here -->
                                        <tr>
                                            <td>Chicken Satay</td>
                                            <td class="text-center">$6.95</td>
                                            <td class="text-center">1</td>
                                            <td class="text-right">$6.95</td>
                                        </tr>
                                        <tr>
                                            <td>Tom Kha soup</td>
                                            <td class="text-center">$7.95</td>
                                            <td class="text-center">1</td>
                                            <td class="text-right">$7.95</td>
                                        </tr>
                                        <tr>
                                            <td>Pad Thai</td>
                                            <td class="text-center">$7.95</td>
                                            <td class="text-center">1</td>
                                            <td class="text-right">$7.95</td>
                                        </tr>
                                        <tr>
                                            <td class="thick-line"></td>
                                            <td class="thick-line"></td>
                                            <td class="thick-line text-center"><strong>Subtotal</strong></td>
                                            <td class="thick-line text-right">$22.85</td>
                                        </tr>
                                        <tr>
                                            <td class="no-line"></td>
                                            <td class="no-line"></td>
                                            <td class="no-line text-center"><strong>Shipping</strong></td>
                                            <td class="no-line text-right">$5.00</td>
                                        </tr>
                                        <tr>
                                            <td class="no-line"></td>
                                            <td class="no-line"></td>
                                            <td class="no-line text-center"><strong>Total</strong></td>
                                            <td class="no-line text-right">$27.85</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>
            <div class="well">

                <div class="btn-group btn-group-justified" role="group" aria-label="...">
                    <div class="btn-group" role="group">
                        <a class="btn btn-default " href="index.php?controller=Business&action=invoke">Close</a>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-default">Delete </button>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-default">Print</button>
                    </div>
                </div>

            </div>
            <hr>


        </div>
    </body>
</html>