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

        <!-- Custom CSS -->
        <link href="css/modern-business.css" rel="stylesheet">
        <link href="css/eatable.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

        <link href="css/datepicker.css" rel="stylesheet">
        <link href="css/datepicker.less" rel="stylesheet" type="text/css" />

        <style>
            body {
                background: #c7c7c7;
            }
        </style>

    </head>

    <body>
        <!-- Navigation bar -->
        <nav class='navbar navbar-inverse navbar-fixed-top' role='navigation'>
            <div class='container' id='navbar-content'>
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class='container navbar-info' >SFSU&nbsp;Software&nbsp;Engineering&nbsp;Project,&nbsp;Fall&nbsp;2015&nbsp;&mdash;&nbsp;For&nbsp;Demonstration&nbsp;Only</div>
                <div class='navbar-infohighlight'></div>
                <div class='navbar-header'>
                    <button type='button' class='navbar-toggle' data-toggle='collapse' data-target='#bs-example-navbar-collapse-1'>
                        <span class='sr-only'>Toggle navigation</span>
                        <span class='icon-bar'></span>
                        <span class='icon-bar'></span>
                        <span class='icon-bar'></span>
                    </button>
                    <a class='navbar-brand img-responsive' style="padding-top: 7px;"><img class="img-responsive" src="img/eatable_hostinterface.png"></a>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class='collapse navbar-collapse' id='bs-example-navbar-collapse-1'>
                    <form class='navbar-form navbar-right' role='admin' id='login_form' method='post' action='index.php'>
                        <ul class="nav navbar-nav navbar-right">
                            <li><a style="color: #f6f6f6;"><em><?php echo $this->restaurant->name; ?></em></a></li>
                            <li><a role='close_window' onclick='window.close()'>Close host interface</a></li>
                            <li><a role='logout' href='index.php?controller=Logout&action=invoke'>Log out</a></li>
                        </ul>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="container-fluid" style="margin: 10px;">
            <div class="row" style="margin-top: 10px; margin-bottom: 10px;">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-sm-3">
                            <h2 style="padding-bottom: 0; margin-top: 15px;">
                                <?php echo $pageTitle; ?>
                            </h2>
                        </div>

                        <div class="col-sm-1">
                            <form role='form' enctype='multipart/form-data' role='refresh_page' method='post' action='index.php?controller=Host&action=invoke'>
                                <?php
                                if (isset($_POST['request_date'])) {
                                    echo "<input type='hidden' name='request_date' value='" . htmlspecialchars($_POST['request_date']) . "' />";
                                }
                                ?>
                                <button type="submit" class="btn btn-default" style="height: 50px; width: 100%;"><i class="glyphicon glyphicon-refresh"></i></button>
                            </form>
                        </div>

                        <?php
                        if (isset($_POST['request_date'])) {
                            echo "
                                <div class='col-sm-1'>
                                    <form role='form' enctype='multipart/form-data' role='refresh_page' method='post' action='index.php?controller=Host&action=invoke'>
                                        <button type='submit' class='btn btn-default' style='height: 50px; width: 100%;'><i class='glyphicon glyphicon-refresh'></i>&nbsp;Today</button>
                                    </form>
                                </div>
                            ";
                        } else {
                            echo "
                            <div class='col-sm-1'></div>
                            ";
                        }
                        ?>

                        <div class="form-group" style="margin-bottom: 0;">
                            <form class="form-horizontal" role="request_reservations_date" method="post" action="index.php?controller=Host&action=invoke">
                                <label for="date" class="col-sm-1 control-label" style="padding-top: 15px;">Change day</label>
                                <div class="col-sm-3">
                                    <!-- start datetimepicker -->
                                    <div class='input-group date' id='datetimepicker_date'>
                                        <input type='text' name="request_date" class="form-control" value="<?php
                                        if (isset($requestDate)) {
                                            echo date("m-d-Y", $requestTimestamp);
                                        } else {
                                            echo date("m-d-Y");
                                        }
                                        ?>" required pattern="[0-9]{2}-[0-9]{2}-[0-9]{4}" title="Use the date picker or type the date in format MM-DD-YYYY, e.g. 03-14-2015"
                                               style="width: 100%; height: 50px;" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                        <button type="submit" class="form-control btn btn-default" style="width: 100%; height: 50px;">Go</button>
                                    </div>
                                    <!-- end datetimepicker -->
                                </div>
                            </form>
                        </div>

                        <div class="col-sm-3">
                            <a data-toggle="modal" data-target="#addReservationModal" class="btn btn-default" style="width: 100%; height: 50px; padding-top: 15px;">
                                <i class="glyphicon glyphicon-plus-sign"></i>
                                New Reservation
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <?php
                        if ($this->reservations) {
                            foreach ($this->reservations as $reservation) {
                                // format date and time in an easily-readable manner
                                $rdate = date("l, F jS, Y", strtotime($reservation->date . " " . $reservation->time));
                                $rtime = date("g:i A", strtotime("01-01-1970 " . $reservation->time));
                                
                                // get restaurant table number from table UID
                                $rtableno = Table::getTableNumberById($reservation->tableId);
                                
                                // load meal order -- will be null if there isn't an order
                                $mealOrder = Order::getOrderByReservationId($reservation->id);
                                ?>

                                <div class='col-sm-2 boxedform' style='margin: 5px; margin-bottom: 15px; padding: 5px; font-size: 1.3em; background: #f6f6f6; line-height: 200%;'>
                                    <h3 class='boxedform-title'><?php
                                        // check if the user is not in the system or was specifically marked as
                                        // an unregistered user (as would be the case if a host took a manual
                                        // reservation)
                                        if ($reservation->userId == Reservation::UNREGISTERED_USER_ID) {
                                            $user = new User();
                                            $user->fname = "";
                                            $user->lname = "(Unregistered)";

                                            // since this was a manual reservation,
                                            // extract customer inforamtion stored in the special instructions field
                                            if (strlen($reservation->specialInstructions) > 0) {
                                                $unregisteredUserInfo = explode("::", $reservation->specialInstructions);
                                                $user->fname = $unregisteredUserInfo[0];
                                                $user->phone_number = $unregisteredUserInfo[1];
                                                $reservation->specialInstructions = $unregisteredUserInfo[2];
                                            }
                                        } else {
                                            $user = User::getUserById($reservation->userId);
                                            if (!$user) {
                                                $user = new User();
                                                $user->fname = "Unregistered";
                                                $user->lname = "User";
                                            }
                                        }
                                        echo $user->fname . " " . $user->lname;
                                        ?></h3>
                                    <em>
                                        <?php
                                        switch ($reservation->status) {
                                            case Reservation::STATUS_PENDING:
                                                echo "Pending";
                                                break;
                                            case Reservation::STATUS_CHECKEDIN:
                                                echo "<span style='color: green;'>Checked in</span>";
                                                break;
                                            case Reservation::STATUS_CANCELLED:
                                                echo "<span style='color: red;'>Cancelled</span>";
                                                break;
                                            case Reservation::STATUS_NOSHOW:
                                                echo "<span style='color: red;'>No show</span>";
                                                break;
                                            default:
                                                echo "Unclassified";
                                        }
                                        ?>
                                    </em>
                                    <?php
                                    if ($mealOrder) {
                                        echo " &mdash; <i class='glyphicon glyphicon-cutlery'></i>";
                                    }
                                    ?>
                                    <div class='row'>
                                        <div class='col-md-12'>
                                            <span title="Time" style="text-nowrap">
                                                <i class='glyphicon glyphicon-time'></i>&nbsp;<strong><?php echo $rtime; ?></strong>&nbsp;&nbsp;
                                            </span>
                                            <span title="Party Size" style="text-nowrap">
                                                <i class='glyphicon glyphicon-user'></i>&nbsp;<strong><?php echo $reservation->partySize ?></strong>&nbsp;&nbsp;
                                            </span>
                                            <span title="Table Number" style="text-nowrap">
                                                <i class='glyphicon glyphicon-flag' title="Table Number"></i>&nbsp;<strong><?php echo $rtableno; ?></strong>
                                            </span>
                                        </div>
                                    </div>

                                    <div class='row' style="margin-top: 15px;">
                                        <?php
                                        if ($reservation->status == Reservation::STATUS_PENDING) {
                                            echo "
                                    <div class='col-lg-4'>
                                        <a data-toggle='modal' data-target='#checkInReservation{$reservation->id}Modal' style='color: #fff;'>
                                            <div class='btn btn-block' style='width: 100%; color: white; background: green;' title='Check in reservation'>
                                                <i class='glyphicon glyphicon-ok'><br/><span style='font-size: 0.75em; font-family: sans-serif;'>Check-in</span></i>
                                            </div>
                                        </a>
                                    </div>

                                    <div class='modal' id='checkInReservation{$reservation->id}Modal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' style='text-align: left;'>
                                        <div class='modal-dialog' role='document'>
                                            <div class='modal-content' style='height: 70%'>
                                                <div class='modal-header'>
                                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                                                    <h4 class='modal-title' id='myModalLabel'>Check In Reservation for $user->fname $user->lname</h4>
                                                </div>
                                                <div class='modal-footer'>
                                                    <div class='form-group'>
                                                        <form role='form' enctype='multipart/form-data' role='set_noshow' id='set_noshow' method='post' action='index.php?controller=Host&action=setCheckInAndView'>
                                                            <input type='hidden' name='id' value='{$reservation->id}' />
                                                            <button type='submit' style='width: 100%; background: #dfffdf;'>Check in and view reservation</button>
                                                        </form>
                                                        <form role='form' enctype='multipart/form-data' role='set_cancel' id='set_cancel' method='post' action='index.php?controller=Host&action=setCheckIn'>
                                                            <input type='hidden' name='id' value='{$reservation->id}' />
                                                            <button type='submit' style='width: 100%; background: #dfffdf;'>Just check in</button>
                                                        </form>
                                                        <br/>
                                                        <button type='button' style='width: 100%;' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>Don't do anything (close window)</span></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class='col-lg-4'>
                                        <a data-toggle='modal' data-target='#cancelReservation{$reservation->id}Modal' style='color: #fff;'>
                                            <div class='btn btn-block' style='width: 100%; color: white; background: red;' title='Mark reservation as cancelled or no show'>
                                                <i class='glyphicon glyphicon-remove'><br/><span style='font-size: 0.75em; font-family: sans-serif;'>Cancel</span></i>
                                            </div>
                                        </a>
                                    </div>

                                    <div class='modal' id='cancelReservation{$reservation->id}Modal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' style='text-align: left;'>
                                        <div class='modal-dialog' role='document'>
                                            <div class='modal-content' style='height: 70%'>
                                                <div class='modal-header'>
                                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                                                    <h4 class='modal-title' id='myModalLabel'>Cancel Reservation for $user->fname $user->lname</h4>
                                                </div>
                                                <div class='modal-footer'>
                                                    <div class='form-group'>
                                                        <form role='form' enctype='multipart/form-data' role='set_noshow' id='set_noshow' method='post' action='index.php?controller=Host&action=setNoShow'>
                                                            <input type='hidden' name='id' value='{$reservation->id}' />
                                                            <button type='submit' style='width: 100%; background: #ffd2d2;'>Mark as no-show</button>
                                                        </form>
                                                        <form role='form' enctype='multipart/form-data' role='set_cancel' id='set_cancel' method='post' action='index.php?controller=Host&action=setCancel'>
                                                            <input type='hidden' name='id' value='{$reservation->id}' />
                                                            <button type='submit' style='width: 100%; background: #ffd2d2;'>Mark as cancelled</button>
                                                        </form>
                                                        <br/>
                                                        <button type='button' style='width: 100%;' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>Don't do anything (close window)</span></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                ";
                                        } else {
                                            echo "<div class='col-lg-8'></div>\n";
                                        }

                                        echo "
                            <div class='col-lg-4'>
                                <a data-toggle='modal' data-target='#viewReservation{$reservation->id}Modal'>
                                    <div class='btn btn-block btn-default' title='View/print reservation'>
                                        <i class='glyphicon glyphicon-eye-open'><br/><span style='font-size: 0.75em; font-family: sans-serif;'>View</span></i>
                                    </div>
                                </a>
                            </div>
                            
                            <div class='modal' id='viewReservation{$reservation->id}Modal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' style='text-align: left;'>
                                <div class='modal-dialog' role='document'>
                                    <div class='modal-content' style='height: 70%'>
                                        <div class='modal-header'>
                                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                                            <h4 class='modal-title' id='myModalLabel'>Reservation Detail</h4>
                                        </div>
                                        <div class='modal-body'>
                                            <p>
                                                <strong>Confirmation Number</strong>: $reservation->id<br/>
                                                <strong>Status</strong>: ";
                                        switch ($reservation->status) {
                                            case Reservation::STATUS_PENDING:
                                                echo "Pending";
                                                break;
                                            case Reservation::STATUS_CHECKEDIN:
                                                echo "<span style='color: green;'>Checked in</span>";
                                                break;
                                            case Reservation::STATUS_CANCELLED:
                                                echo "<span style='color: red;'>Cancelled</span>";
                                                break;
                                            case Reservation::STATUS_NOSHOW:
                                                echo "<span style='color: red;'>No show</span>";
                                                break;
                                            default:
                                                echo "Unclassified";
                                        }


                                        echo "<br/>
                                                <strong>Name</strong>: $user->fname $user->lname<br/>
                                                <strong>Phone Number</strong>: $user->phone_number<br/>
                                                <strong>Party Size</strong>: $reservation->partySize<br/>
                                                <strong>Date</strong>: $rdate<br/>
                                                <strong>Time</strong>: $rtime<br/>
                                                <strong>Assigned Table Number</strong>: $rtableno<br/>";
                                        if (strlen($reservation->specialInstructions) > 0) {
                                            echo "<strong>Special Instructions</strong>:<br/> $reservation->specialInstructions";
                                        }
                                        if ($mealOrder) {
                                            echo "<br/><br/><strong>Meal Order</strong><br/>
                                            <table width='100%' class='table table-striped'>
                                                <tr>
                                                    <th>Item</th>
                                                    <th>Quantity</th>
                                                </tr>
                                                ";
                                            foreach($mealOrder as $item) {
                                                $menuItem = MenuItem::getMenuItemById($item->menuItemId);
                                                echo "<tr>
                                                    <td>$menuItem->description</td>
                                                    <td>$item->quantity</td>
                                                </tr>
                                                ";
                                            }
                                        }
                                        echo "
                                            </table>
                                            </p>
                                        </div>
                                        <div class='modal-footer'>
                                            <div class='form-group'>
                                                <button type='button' style='width: 100%;' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>Close</span></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            ";
                                        ?>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='6'><h4><strong>There are no reservations for the selected day.</strong></h4></td></tr>\n";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="footer">
            <div class="container container-footer">
                <div class='row'>
                    <div class='col-sm-6'><a class="foot-text">Copyright &copy; eaTable 2015</a></div>
                    <div class='col-sm-2 textalign-right'></div>
                    <div class='col-sm-2 textalign-right'>
                    </div>
                    <div class='col-sm-2 textalign-right'></div>
                </div>
            </div>
        </footer>

        <!-- Manual add reservation modal -->
        <div class="modal" id="addReservationModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form class="form-horizontal" enctype='multipart/form-data' role='additem' id='add_reservation_form' method='post' action='index.php?controller=Host&action=addReservation'>

                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Manual Add Reservation</h4>
                        </div>

                        <div class="modal-body">
                            <p><strong>Warning</strong>: This form does not validate whether this reservation would conflict with an existing one. It is does, however, reflect
                                the ability of the restaurant to take future online reservations.</p>

                            <div class="form-group">
                                <label for="name" class="col-sm-3 control-label">Full Name</label>
                                <div class="col-sm-8">
                                    <input type='text'class='form-control' name='name' placeholder='' required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="phone_number" class="col-sm-3 control-label">Phone number</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="phone_number" name="phone_number" aria-describedby="phone-format" required aria-required="true" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="415-555-1234" title="Area code and number, e.g. 415-555-1234">
                                    <span id="phone-format" class="help">Format: Area code and number, e.g. 415-555-1234</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="date" class="col-sm-3 control-label">Date</label>
                                <div class="col-sm-8">
                                    <input type='date' size='10' class='form-control' name='date' placeholder='12/01/2015' required>
                                    <!-- Output format is YYYY-MM-DD, e.g. "2015-11-16" is November 16th, 2015.-->
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="time" class="col-sm-3 control-label">Time</label>
                                <div class="col-sm-8">
                                    <input type='time' class='form-control' name='time' placeholder='7:00 PM' required>
                                    <!-- Output format is 7:00pm -> "19%3A00"  -->
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="party_size" class="col-sm-3 control-label">Party Size</label>
                                <div class="col-sm-8">
                                    <input type='number' min='1' max='12' size='2' class='form-control' name='party_size' placeholder='' required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-sm-3 control-label">Table Number</label>
                                <div class="col-sm-8">
                                    <input type='number' min='1' max='99' size='2' class='form-control' name='table_number' placeholder='' required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="special_instructions" class="col-sm-3 control-label">Special Instructions (optional)</label>
                                <div class="col-sm-8">
                                    <textarea class='form-control' rows='3' name='special_instructions'></textarea>
                                </div>
                            </div>

                            <input type='hidden' name='status_id' value='2'>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type='submit' class='btn btn-primary'>Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>



        <!-- jQuery Version 1.11.0 -->
        <script src="js/jquery-1.11.0.js"></script>

        <!-- depencencies for Datetimepicker -->
        <script type="text/javascript" src="js/moment.min.js"></script>
        <script type="text/javascript" src="js/transition.js"></script>
        <script type="text/javascript" src="js/collapse.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="js/bootstrap.min.js"></script>

        <!-- Bootstrap Datetimepicker -->
        <script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
        <script type="text/javascript" src="js/search.js"></script>

        <!-- JavaScript common to all views -->
        <script type="text/javascript" src="js/eatable.js"></script>

        <script type="text/javascript">
                                $('#datetimepicker_date').datetimepicker({
                                    format: 'MM-DD-YYYY'
                                });
<?php
if (isset($loadViewId)) {
    echo "
                $(window).load(function() {
                    $('#viewReservation{$loadViewId}Modal').modal('show');
                });
            ";
}
?>
        </script>


    </body>
</html>
