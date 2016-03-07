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
    </head>

    <body>
        <!-- Navigation bar -->
        <?php include_once('view/module/navigation.php')?>

        <!-- Page Content -->
        <div class="container">
            <?php include_once('view/module/admin_tabs.php') ?>
            <div class="row">
                <div class="col-md-12">
                    <p><strong>Tip</strong>: The <code>Check availability...</code> button below has a debug feature which will allow you to see the reservation algorithm in action, step by step.</p>
                    
                    <!-- Filter by -->
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-12">Filter by <strong>status</strong></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <a class="btn btn-sm btn-default btn-block<?php if ($filterValue === NIL) { echo ' active'; }?>" href="index.php?controller=AdminReservations&action=invoke">All</a>
                            </div>
                            <div class="col-md-2">
                                <a class="btn btn-sm btn-default btn-block<?php if ($filterValue === 2) { echo ' active'; }?>" href="index.php?controller=AdminReservations&action=invoke&filterProperty=status_id&filterValue=2&filterFlagNumber=true">Pending</a>
                            </div>
                            <div class="col-md-2">
                                <a class="btn btn-sm btn-default btn-block<?php if ($filterValue === 3) { echo ' active'; }?>" href="index.php?controller=AdminReservations&action=invoke&filterProperty=status_id&filterValue=3&filterFlagNumber=true">Checked in</a>
                            </div>
                            <div class="col-md-2">
                                <a class="btn btn-sm btn-default btn-block<?php if ($filterValue === 4) { echo ' active'; }?>" href="index.php?controller=AdminReservations&action=invoke&filterProperty=status_id&filterValue=4&filterFlagNumber=true">Cancelled</a>
                            </div>
                            <div class="col-md-2">
                                <a class="btn btn-sm btn-default btn-block<?php if ($filterValue === 5) { echo ' active'; }?>" href="index.php?controller=AdminReservations&action=invoke&filterProperty=status_id&filterValue=5&filterFlagNumber=true">No-show</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Report status of performed operations, if applicable -->
                    <p>
                        <?php
                        if (isset($removedId)) {
                            echo "Removal of reservation record <strong>{$removedId}</strong> successful.";
                        }
                        elseif (isset($removedIdSuccess) && !$removedIdSuccess) {
                            echo "Reservation record <strong>{$removedId}</strong> was not removed.";
                        }
                        
                        if (isset($addedSuccess)) {
                                echo $addedSuccess;
                            }
                        ?>
                    </p>

                    <!-- Button to open "Check availability" modal -->
                    <a class="btn btn-default" data-toggle="modal" data-target="#checkAvailModal"><i class="glyphicon glyphicon-info-sign"></i>&nbsp;&nbsp;Check availability...</a>
                    <!-- Button to open "Manual add reservation" modal -->
                    <a class="btn btn-default" data-toggle="modal" data-target="#manualAddModal"><i class="glyphicon glyphicon-plus-sign"></i>&nbsp;&nbsp;Place reservation...</a>

                    <!-- Show reservations -->
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><a href="index.php?controller=AdminReservations&action=invoke&sort=id">UID</a></th>
                                <th><a href="index.php?controller=AdminReservations&action=invoke&sort=status_id">Status</a></th>
                                <th><a href="index.php?controller=AdminReservations&action=invoke&sort=user_id">User UID</a></th>
                                <th><a href="index.php?controller=AdminReservations&action=invoke&sort=restaurant_id">Restaurant (UID)</a></th>
                                <th><a href="index.php?controller=AdminReservations&action=invoke&sort=date">Date</a></th>
                                <th><a href="index.php?controller=AdminReservations&action=invoke&sort=time">Time</a></th>
                                <th><a href="index.php?controller=AdminReservations&action=invoke&sort=party_size">Party Size</a></th>
                                <th><a href="index.php?controller=AdminReservations&action=invoke&sort=table_id">Table UID</a></th>
                                <th>Special Instructions</th>
                                <th>Meal Order</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($reservations) {
                                foreach ($reservations as $reservation) {
                                    switch ($reservation->status) {
                                        case 1:
                                            $statusText = 'Unplaced';
                                            break;
                                        case 2:
                                            $statusText = 'Pending';
                                            break;
                                        case 3:
                                            $statusText = 'Checked in';
                                            break;
                                        case 4:
                                            $statusText = 'Cancelled';
                                            break;
                                        case 5:
                                            $statusText = 'No show';
                                            break;
                                        default:
                                            $statusText = 'N/A';
                                    }
                                    
                                    $rName = Restaurant::getPropertyById("name", $reservation->restaurantId);
                                    
                                    echo "<tr>
                                            <td>{$reservation->id}</td>
                                            <td>{$statusText}</td>
                                            <td>{$reservation->userId}</td>
                                            <td>{$rName} ({$reservation->restaurantId})</td>
                                            <td>{$reservation->date}</td>
                                            <td>{$reservation->time}</td>
                                            <td>{$reservation->partySize}</td>
                                            <td>{$reservation->tableId}</td>
                                            <td>{$reservation->specialInstructions}</td>
                                            <td>{$reservation->mealOrder}</td>
                                            <td><a data-toggle='modal' data-target='#removeReservation{$reservation->id}Modal'><i class='glyphicon glyphicon-remove'></i></a></td>
                                        </tr>";
                                    echo "
                                    <div class='modal fade' id='removeReservation{$reservation->id}Modal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
                                        <div class='modal-dialog' role='document'>
                                            <div class='modal-content' style='height: 70%'>
                                                <div class='modal-header'>
                                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                                                    <h4 class='modal-title' id='myModalLabel'>Remove Reservation</h4>
                                                    <p><strong>Warning</strong>: This operation cannot be undone.</p>
                                                </div>
                                                <div class='modal-body'>
                                                    Really remove reservation record {$reservation->id} (table for {$reservation->partySize} at {$rName} (restaurant ID {$reservation->restaurantId}) on {$reservation->date} {$reservation->time})?
                                                </div>
                                                <div class='modal-footer'>
                                                    <button type='button' class='btn btn-default' data-dismiss='modal'>Do not remove</button>
                                                    <a class='btn btn-danger' href='index.php?controller=AdminReservations&action=removeReservation&id={$reservation->id}'>Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>";
                                }
                            } else {
                                echo "<tr><td colspan=9>No reservations.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <?php include_once('view/module/footer.php'); ?>

        <!-- Manual add reservation modal -->
        <div class="modal fade" id="manualAddModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form class="form-horizontal" enctype='multipart/form-data' role='additem' id='add_reservation_form' method='post' action='index.php?controller=AdminReservations&action=addReservation'>

                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Manual Add Reservation</h4>
                            <p><strong>Warning</strong>: For testing purposes only. Form has minimal validation.</p>
                        </div>

                        <div class="modal-body">
                            <div class="form-group">
                                <label for="email" class="col-sm-3 control-label">General User UID</label>
                                <div class="col-sm-8">
                                    <input type='number' min='1' size='8' class='form-control' name='user_id' placeholder=''>
                                    This refers to the person making the reservation.
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-sm-3 control-label">Restaurant UID</label>
                                <div class="col-sm-8">
                                    <input type='number' min='1' size='8' class='form-control' name='restaurant_id' placeholder=''>
                                    Ensure restaurant ID is valid or the reservation will not be placed.
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-sm-3 control-label">Date</label>
                                <div class="col-sm-8">
                                    <input type='date' size='10' class='form-control' name='date' placeholder='YYYY-MM-DD'>
                                    <!-- Output format is YYYY-MM-DD, e.g. "2015-11-16" is November 16th, 2015.-->
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-sm-3 control-label">Time</label>
                                <div class="col-sm-8">
                                    <input type='time' class='form-control' name='time' placeholder='HH:MM:SS'>
                                    Reservations should begin on the hour or half-hour, e.g. 11:00am or 7:30pm. If there is no browser validation, use
                                    format HH:MM:SS.
                                    <!-- Output format is 7:00pm -> "19%3A00"  -->
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-sm-3 control-label">Party Size</label>
                                <div class="col-sm-8">
                                    <input type='number' min='1' size='2' class='form-control' name='party_size' placeholder=''>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-sm-3 control-label">Table UID</label>
                                <div class="col-sm-8">
                                    <input type='number' min='1' size='8' class='form-control' name='table_id' placeholder=''>
                                    <em>Note</em>: Not table number. Use the table's unique ID.
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-sm-3 control-label">Special Instructions (optional)</label>
                                <div class="col-sm-8">
                                    <textarea class='form-control' rows='3' name='special_instructions'></textarea>
                                </div>
                            </div>

<!--                            <div class="form-group">
                                <label for="email" class="col-sm-3 control-label">Status</label>
                                <div class="col-sm-8">
                                    <input type='number' size='1' min='1' max='5' class='form-control' name='status_id' placeholder=''>
                                    <em>Possible values</em>: 1 - unplaced, 2 - pending, 3 - checkedin, 4 - cancelled, 5 - noshow 
                                </div>
                            </div>-->
                            <input type='hidden' name='status_id' value='2'>

                            <div class="form-group">
                                <label for="email" class="col-sm-3 control-label">Meal Order (optional) </label>
                                <div class="col-sm-8">
                                    <textarea class='form-control' rows='3' name='meal_order'></textarea>
                                    Format is <em>menuItemId,quantity;menuItemId,quantity;</em>
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type='submit' class='btn btn-primary'>Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Check availability modal -->
        <div class="modal fade" id="checkAvailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form class="form-horizontal" enctype='multipart/form-data' role='additem' id='check_avail_form' method='post' action='index.php?controller=AdminReservations&action=checkAvailability'>

                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Check Reservation Availability</h4>
                            <p><strong>Warning</strong>: For testing purposes only. Form has minimal validation.</p>
                        </div>

                        <div class="modal-body">

                            <div class="form-group">
                                <label for="email" class="col-sm-3 control-label">Restaurant ID</label>
                                <div class="col-sm-8">
                                    <input type='number' min='1' size='8' class='form-control' name='restaurant_id' placeholder='' value='34'>
                                    Ensure restaurant ID is valid or the reservation will not be placed.
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-sm-3 control-label">Date</label>
                                <div class="col-sm-8">
                                    <input type='date' size='10' class='form-control' name='date' placeholder='YYYY-MM-DD' value='2015-12-14'>
                                    <!-- Output format is YYYY-MM-DD, e.g. "2015-11-16" is November 16th, 2015.-->
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-sm-3 control-label">Time</label>
                                <div class="col-sm-8">
                                    <input type='time' class='form-control' name='time' placeholder='HH:MM:SS' value='19:00:00'>
                                    Reservations should begin on the hour or half-hour, e.g. 11:00am or 7:30pm. If there is no browser validation, use
                                    format HH:MM:SS.
                                    <!-- Output format is 7:00pm -> "19%3A00"  -->
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-sm-3 control-label">Party Size</label>
                                <div class="col-sm-8">
                                    <input type='number' min='1' size='2' class='form-control' name='party_size' placeholder='' value='2'>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-8">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" value="true" name="debug">
                                                Show debug text
                                            </label>

                                        </div>
                                    </div>
                                </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type='submit' class='btn btn-primary' id='checkavail-button'>Check</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- jQuery Version 1.11.0 -->
        <script src="js/jquery-1.11.0.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>
