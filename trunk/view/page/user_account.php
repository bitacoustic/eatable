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
<!--        <style>
            .form-inline .form-group {
                margin-left: 0;
                margin-right: 0;
            }
            
            .form-inline .form-group input {
                width: 175px;
            }
        </style>-->
    </head>

    <body>
        <!-- Navigation bar -->
        <?php include_once('view/module/navigation.php') ?>	

        <!-- Page Content -->
        <div class="container">
            <div class="row rolenav">
                <div class="col-lg-12">
                    <h1>General User Account</h1>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 form-horizontal">
                    <h2>Upcoming Reservations</h2>
                    <div class="boxedform">    
                        <?php
                        if (isset($canceledSuccess)) {
                            echo "<p>" . $canceledSuccess . "</p>";
                        }
                        ?>

                        <!-- Show reservations -->
                        <!-- TODO: show only pending, or show different statuses? -->
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Restaurant</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Party&nbsp;Size</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($this->reservations) {
                                    foreach ($this->reservations as $reservation) {
                                        $restaurantName = Restaurant::getPropertyById("name", $reservation->restaurantId);
                                        $timestamp = strtotime($reservation->date . " " . $reservation->time);
                                        $date = date("l, F dS, Y", $timestamp);
                                        $time = date("g:i A", $timestamp);

                                        if ($timestamp >= time()) {
                                            echo "<tr>
                                            <td>{$restaurantName}</td>
                                            <td>{$date}</td>
                                            <td>{$time}</td>
                                            <td>{$reservation->partySize}</td>";
                                            if ($reservation->status == Reservation::STATUS_CANCELLED) {
                                                echo "<td><strong>Cancelled</strong></td>\n</tr>\n";
                                            } else {
                                                echo "<td><a data-toggle='modal' data-target='#removeReservation{$reservation->id}Modal'><i class='glyphicon glyphicon-remove'></i>&nbsp;Cancel</a></td>    
                                        </tr>";
                                                echo "
                                            <div class='modal fade' id='removeReservation{$reservation->id}Modal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
                                                <div class='modal-dialog' role='document'>
                                                    <div class='modal-content' style='height: 70%'>
                                                        <div class='modal-header'>
                                                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                                                            <h4 class='modal-title' id='myModalLabel'>Cancel Reservation</h4>
                                                            <p><strong>Warning</strong>: This operation cannot be undone.</p>
                                                        </div>
                                                        <div class='modal-body'>
                                                            Really cancel table for {$reservation->partySize} at {$restaurantName} on {$date} at {$time}?
                                                        </div>
                                                        <div class='modal-footer'>
                                                            <button type='button' class='btn btn-default' data-dismiss='modal'>Do not cancel</button>
                                                            <a class='btn btn-danger' href='index.php?controller=Account&action=cancelReservation&id={$reservation->id}'>Cancel (this cannot be undone)</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>";
                                            }
                                        }
                                    }
                                } else {
                                    echo "<tr><td colspan=9>You have no upcoming reservations.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="col-md-6">                    
                    <h2>Settings</h2>
                    <!-- Basic information -->
                    <form role="form" class="form-horizontal boxedform" method="get" action="index.php">
                        <!-- Passes the controller and action to index.php -->
                        <input type="hidden" name="controller" value="Account">
                        <input type="hidden" name="action" value="updateUserAccount">

                        <!-- Beginning of form --> 
                        <h3 class="boxedform-title">Basic Information</h3>

                        <div class="form-group">
                            <label for="fname" class="col-xs-3 control-label">First name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="fname" name="fname" placeholder="First name" value="<?php echo $this->user->fname ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="lname" class="col-sm-3 control-label">Last name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="lname" name="lname" placeholder="Last name" value="<?php echo $this->user->lname; ?>">
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email" class="col-sm-3 control-label">Email address</label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control" id="email" name="email" placeholder="test@example.com" value="<?php echo $this->user->email ?>">
                            </div>
                        </div>

                        <!-- Phone number -->
                        <div class="form-group">
                            <label for="phone" class="col-sm-3 control-label">Phone number</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="XXX-XXX-XXXX" value="<?php echo $this->user->phone_number ?>">
                            </div>
                        </div>

                        <!-- Save changes -->
                        <div class="form-group">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-8">
                                <button type="submit" class="btn btn-default btn-primary">Save changes</button>
                            </div>
                        </div>
                    </form>

                    <!-- Change password -->
                    <form role="form" class="form-horizontal boxedform" method="post" action="index.php">    
                        <!-- Passes the controller and action to index.php -->
                        <input type="hidden" name="controller" value="Account">
                        <input type="hidden" name="action" value="updateUserPassword">

                        <h3 class="boxedform-title">Change password</h3>

                        <!-- Current password -->
                        <div class="form-group">
                            <label for="currentpassword" class="col-sm-3 control-label">Current password</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" id="currentpassword" name="currentPassword">
                            </div>
                        </div>

                        <!-- New password -->
                        <div class="form-group">
                            <label for="newpassword" class="col-sm-3 control-label">New password</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" id="newpassword" name="newPassword">
                            </div>
                        </div>

                        <!-- New password confirmation -->
                        <div class="form-group">
                            <label for="newpasswordconfirm" class="col-sm-3 control-label">New password (confirm)</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" id="newpasswordconfirm" name="newPasswordConfirm">
                            </div>
                        </div>

                        <!-- Change password button -->
                        <div class="form-group">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-8">
                                <button type="submit" class="btn btn-default btn-primary">Change password</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <?php include_once('view/module/footer.php'); ?>

        <!-- jQuery Version 1.11.0 -->
        <script src="js/jquery-1.11.0.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>
