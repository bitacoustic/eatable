<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>eaTable</title>

        <!-- Bootstrap Core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <link href="css/datepicker.css" rel="stylesheet"> 
        <link href="css/datepicker.less" rel="stylesheet" type="text/css" />

        <!-- Custom CSS -->
        <link href="css/modern-business.css" rel="stylesheet">
        <link href="css/eatable.css" rel="stylesheet">
    </head>

    <body>
        <!-- Navigation bar -->
        <?php include_once('view/module/navigation.php') ?>

        <!-- Page Content -->
        <div class="container">
            <!-- Page Heading/Breadcrumbs -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Confirm Reservation, ID :<?php echo $this->reservation->id ?></h1>
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

            <hr>
            
            <form action="index.php" method="GET" id="confirmForm">
                <div style="text-align: center ">
                    <button class="btn btn-primary" type="submit">Finish Reservation</button>
                    <input type="hidden" id='controllerID' name="controller" value="Landing">
                    <input type="hidden" name="action" value="invoke">
                </div>
            </form>
            
        </div>

        <!-- Footer -->
        <?php include_once('view/module/footer.php'); ?>

        <!-- jQuery Version 1.11.0 -->
        <script src="js/jquery-1.11.0.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="js/bootstrap.min.js"></script>

        <script type="text/javascript" src="js/bootstrap-datepicker.js"></script>



        <script type="text/javascript">
            function submitReservation()
            {
                document.getElementById("confirmForm").submit();
            }
        </script>
    </body>
</html>
