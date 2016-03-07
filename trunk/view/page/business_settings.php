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

        <!-- Datetimepicker -->
        <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css">

        <!-- Custom CSS -->
        <link href="css/modern-business.css" rel="stylesheet">
        <link href="css/eatable.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    </head>

    <body>
        <!-- Navigation bar -->
        <?php include_once('view/module/navigation.php') ?>

        <!-- Page Content -->
        <div class="container">
            <?php include_once('view/module/business_tabs.php') ?>

            <div class="row">
                <div class="col-md-6">  
                    <h2>Restaurant Settings</h2>

                    <div class="form-horizontal boxedform">
                        <h3 class="boxedform-title">Approval Status</h3>
                        <p>
                            <?php
                            if ($this->restaurant->approved) {
                                echo "<h4>Approved</h4>
                                Congratulations, your restaurant is visible to the public. You are currently able to accept new reservations.
                                ";
                            } else {
                                echo "<h4>Pending</h4>
                                If this is your first time visiting this page, it's time to fill out your restaurant's information.
                                <ul>
                                    <li>Step 1: Fill out this page (Edit Settings).</li>
                                    <li>Step 2: Fill out the next page, titled Edit Menu. (This is an optional feature.)</li>
                                    <li>Step 3: Wait for approval.</li>
                                </ul>
                                Your restaurant is not yet visible to the public, meaning general users won't be able to see your restaurant details
                                or place a new reservation. Administrators check pending restaurants periodically and upgrade them to
                                <strong>approved</strong> once your restaurant information has been verified to be valid and complete.
                                ";
                            }
                            ?>
                        </p>
                    </div>

                    <!-- Basic information -->
                    <form role="form" class="form-horizontal boxedform" enctype='multipart/form-data' role='update_restaurant' id='update_restaurant_form' method='post' action='index.php?controller=BusinessSettings&action=updateRestaurant'>
                        <h3 class="boxedform-title">Basic Information</h3>

                        <div class="form-group">
                            <label for="rname" class="col-xs-3 control-label">Restaurant name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="rname" name="rname" placeholder="Restaurant" value="<?php echo $this->restaurant->name; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address" class="col-sm-3 control-label">Address</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="address" name="address" placeholder="123 Pepper St" value="<?php echo $this->restaurant->addressStreet; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="city" class="col-sm-3 control-label">City</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="city" name="city" placeholder="San Francisco" value="<?php echo $this->restaurant->addressCity; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="state" class="col-sm-3 control-label">State</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="state" name="state" placeholder="California" value="<?php echo $this->restaurant->addressState; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="zip" class="col-sm-3 control-label">Postal Code</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="zip" name="zip" placeholder="94132" value="<?php echo $this->restaurant->addressPostalCode; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="phone" class="col-sm-3 control-label">Phone number</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="phone" name="phone" placeholder="XXX-XXX-XXXX" value="<?php echo $this->restaurant->phoneNumber; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="cuisine_type" class="col-sm-3 control-label">Cuisine type</label>
                            <div class="col-sm-8">   
                                <select class="form-control" id="cuisine" name="cuisine">
                                    <option selected="selected" value="<?php echo $this->restaurant->cuisineType; ?>"><?php echo $this->restaurant->cuisineType; ?></option>
                                    <option disabled="disabled">-------</option>
                                    <?php include 'view/module/cuisine_options.php'; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="keywords" class="col-sm-3 control-label">Keywords</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="keywords" name="keywords" placeholder="pizza, pasta" value="<?php echo $this->restaurant->keywords; ?>">
                                Comma-delimited keywords, e.g. "italian, pizza, seafood, soup".
                            </div>
                        </div>

                        <!-- Save changes -->
                        <div class="form-group">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-8">
                                <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-disk"></i>&nbsp;&nbsp;Save changes</button>
                            </div>
                        </div>

                        <?php
                        if (isset($updateSuccess)) {
                            if ($updateSuccess) {
                                echo "<p>Restaurant information was successfully updated.</p>";
                            } else {
                                echo "<p>There was an error updating the restaurant record.</p>";
                            }
                        }
                        ?>
                    </form>

                    <form class="form-horizontal boxedform" enctype='multipart/form-data' role='update_image' id='update_image_form' method='post' action='index.php?controller=BusinessSettings&action=updateImage'>
                        <h3 class="boxedform-title">Image</h3>
                        <div class="form-group">
                            <div class="col-sm-3 control-label"><strong>Current Image</strong></div>
                            <div class="col-sm-8">
                                <img class="img-responsive" src="<?php echo $this->restaurant->getImage(); ?>" style="width: 100%; position: relative; margin-bottom: 30px; left: 50%; transform: translate(-50%);">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3 control-label"><strong>New Image</strong></div>
                            <div class="col-sm-8">
                                <input type='file' name='newimage' id='newimage' class='form-control' style='border: 0'>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-8">
                                <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-upload"></i>&nbsp;&nbsp;Upload new image</button>
                            </div>
                        </div>

                        <?php
                        if (isset($updateImgSuccess)) {
                            if ($updateImgSuccess) {
                                echo "<p>Restaurant image was successfully updated.</p>";
                            } else {
                                echo "<p>There was an error updating the restaurant image.</p>";
                            }
                        }
                        ?>
                    </form>
                </div>
                <div class="col-md-6">
                    <h2>Operation Information</h2>

                    <div class="form-horizontal boxedform" style="text-align: center;">
                        <h3 class="boxedform-title">Open Hours</h3>

                        <table class="table table-striped">
                            <?php
                            // Initialize an array of key, value pairs to count the number of hours ranges for each day
                            $countRangesPerDay = array(0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0);
                            if ($this->hours) {
                                foreach ($this->hours as $range) {
                                    $countRangesPerDay[$range->dayOfWeek] ++;
                                    $dayOfWeek = RestaurantHours::getDayOfWeekString($range->dayOfWeek);
                                    $start = date("g:i A", strtotime("2015-01-01 " . $range->startTime));
                                    $end = date("g:i A", strtotime("2015-01-01 " . $range->endTime));
                                    echo "<tr>
                                        <td><strong>{$dayOfWeek}</strong></td>
                                        <td>{$start}</td>
                                        <td>to</td>
                                        <td>{$end}</td>
                                        <td>
                                            <strong>
                                                <a data-toggle='modal' data-target='#editRange{$range->id}Modal'><i class='glyphicon glyphicon-pencil'></i>&nbsp;Edit</a> &nbsp; 
                                                <a data-toggle='modal' data-target='#removeRange{$range->id}Modal'><i class='glyphicon glyphicon-remove'></i>&nbsp;Remove</a> &nbsp; 
                                            </strong>
                                        </td>
                                    </tr>";

                                    echo "
                                    <div class='modal fade' id='editRange{$range->id}Modal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' style='text-align: left;'>
                                        <div class='modal-dialog modal-lg' role='document'>
                                            <div class='modal-content'>
                                                <form class='form-horizontal' enctype='multipart/form-data' role='additem' id='edit_range_form' method='post' action='index.php?controller=BusinessSettings&action=editRange'>
                                                    <div class='modal-header'>
                                                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                                                        <h4 class='modal-title' id='myModalLabel'>Edit hours range</h4>
                                                    </div>

                                                    <div class='modal-body' style='min-height: 450px;'>
                                                        <p>Edit hours range for {$dayOfWeek}.</p>
                                                        <p><strong>Restrictions</strong>: Hours extending to or past midnight will be split into the next day in a future update; for now, this must be done manually -- i.e. the range should end, at the latest, at 11:59pm. Ensure the end time is later than the start time.</p>
                                                        <p></p>
                                                        <input type='hidden' class='form-control' name='restaurant_id' value='{$this->restaurant->id}' />
                                                        <input type='hidden' class='form-control' name='id' value='{$range->id}' />
                                                        <div class='form-group'>
                                                            <label for='day' class='col-sm-3 control-label'>Day of the week</label>
                                                            <div class='col-sm-8'>
                                                                <input disabled='disabled' class='form-control' value='{$dayOfWeek}' />
                                                                <input type='hidden' value='{$range->dayOfWeek}' name='day' />
                                                            </div>
                                                        </div>

                                                        <div class='form-group'>
                                                            <label for='start' class='col-sm-3 control-label'>Start time</label>
                                                            <div class='col-sm-3'>
                                                                <!-- start datetimepicker -->
                                                                <div class='form-group'>
                                                                    <div class='input-group date' id='dtp_editstart{$range->id}'>
                                                                        <input type='text' name='start' class='form-control' value='{$start}' required pattern='[0-9]{1,2}:[0-9]{2} [AaPp][Mm]' title='e.g. 10:30 PM, 07:30 am, or 9:45 PM' />
                                                                        <span class='input-group-addon'>
                                                                            <span class='glyphicon glyphicon-time'></span>
                                                                        </span>
                                                                    </div>
                                                                    Current: {$start}
                                                                </div>
                                                                <!-- end datetimepicker -->
                                                            </div>
                                                            <label for='end' class='col-sm-2 control-label'>End time</label>
                                                            <div class='col-sm-3'>
                                                                <!-- start datetimepicker -->
                                                                <div class='form-group'>
                                                                    <div class='input-group date' id='dtp_editend{$range->id}'>
                                                                        <input type='text' name='end' class='form-control' value='{$end}' required pattern='[0-9]{1,2}:[0-9]{2} [AaPp][Mm]' title='e.g. 10:30 PM, 07:30 am, or 9:45 PM' />
                                                                        <span class='input-group-addon'>
                                                                            <span class='glyphicon glyphicon-time'></span>
                                                                        </span>    
                                                                    </div>
                                                                    Current: {$end}
                                                                </div>
                                                                <!-- end datetimepicker -->
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class='modal-footer'>
                                                        <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
                                                        <button type='submit' class='btn btn-primary'>Save changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    ";

                                    // Remove range modal
                                    echo "
                                    <div class='modal fade' id='removeRange{$range->id}Modal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' style='text-align: left;'>
                                        <div class='modal-dialog' role='document'>
                                            <div class='modal-content' style='height: 70%'>
                                                <div class='modal-header'>
                                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                                                    <h4 class='modal-title' id='myModalLabel'>Remove Restaurant Table</h4>
                                                </div>
                                                <div class='modal-body'>
                                                    <p>Really remove restaurant hours range: $dayOfWeek, $start to $end?</p>
                                                    <p><strong>Warning</strong>: This operation cannot be undone. Changing hours and table information affects the ability to take
                                                        future table reservations, but does not automatically cancel reservations already in the system.</p>
                                                </div>
                                                <div class='modal-footer'>
                                                    <form role='form' enctype='multipart/form-data' role='remove_table' id='remove_table_form' method='post' action='index.php?controller=BusinessSettings&action=removeRange'>
                                                        <button type='button' class='btn btn-default' data-dismiss='modal'>Do not remove</button>
                                                        <input type='hidden' name='id' value='{$range->id}'/>
                                                        <button type='submit' class='btn btn-danger'>Remove</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>\n";
                                }
                            }


                            // Show days that do not have open hours to indicate that they are closed
                            for ($i = 0; $i < count($countRangesPerDay); $i++) {
                                if ($countRangesPerDay[$i] == 0) {
                                    $dayOfWeek = RestaurantHours::getDayOfWeekString($i);
                                    echo "<tr>
                                    <td>
                                        <strong>{$dayOfWeek}</strong>
                                    </td>

                                    <td colspan='3'>
                                        <em>closed</em>
                                    </td>

                                    <td></td>
                                </tr>\n";
                                }
                            }
                            ?>
                        </table>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <a  class="btn btn-primary" data-toggle='modal' data-target='#addHoursRangeModal'><i class="glyphicon glyphicon-plus"></i>&nbsp;&nbsp;Add range ...</a>
                            </div>
                        </div>
                    </div>

                    <div href="#tables" role="form" class="form-horizontal boxedform" style="text-align: center;">
                        <h3 class="boxedform-title">Tables</h3>
                        <table class="table table-striped table-condensed">
                            <?php
                            if ($this->tables) {
                                echo "<tr>
                                    <th>Number</th>
                                    <th>Seating Capacity</th>
                                    <th></th>
                                </tr>";
                                foreach ($this->tables as $table) {
                                    echo "<tr>
                                        <td>{$table->tableNumber}</th>
                                        <td>{$table->seatingCapacity}</th>
                                        <td class='textalign-right'>
                                            <strong>
                                                <a data-toggle='modal' data-target='#editTable{$table->id}Modal'><i class='glyphicon glyphicon-pencil'></i>&nbsp;Edit</a> &nbsp; 
                                                <a data-toggle='modal' data-target='#removeTable{$table->id}Modal'><i class='glyphicon glyphicon-remove'></i>&nbsp;Remove</a> &nbsp; 
                                            </strong>
                                        </td>
                                    </tr>";

                                    // Edit table modal
                                    echo "
                                    <div class='modal fade' id='editTable{$table->id}Modal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' style='text-align: left;'>
                                        <form role='form' enctype='multipart/form-data' role='remove_table' id='remove_table_form' method='post' action='index.php?controller=BusinessSettings&action=editTable'>
                                            <div class='modal-dialog' role='document'>
                                                <div class='modal-content' style='height: 70%'>
                                                    <div class='modal-header'>
                                                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                                                        <h4 class='modal-title' id='myModalLabel'>Edit restaurant table {$table->tableNumber}</h4>
                                                    </div>
                                                    <div class='modal-body'>
                                                        <div class='form-group'>
                                                            <label for='email' class='col-sm-3 control-label'>Current Seating Capacity</label>
                                                            <div class='col-sm-8'>
                                                                <input class='form-control' disabled='disabled' value='{$table->seatingCapacity}'>
                                                            </div>
                                                        </div>
                                                        <div class='form-group'>
                                                            <label for='email' class='col-sm-3 control-label'>New Seating Capacity</label>
                                                            <div class='col-sm-8'>
                                                                <input type='number' min='1' max='12' size='2' class='form-control' name='seating_capacity' value='{$table->seatingCapacity}'>
                                                                Valid range is 1-12.
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class='modal-footer'>
                                                        <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
                                                        <input type='hidden' name='id' value='{$table->id}'/>
                                                        <input type='hidden' name='table_number' value='{$table->tableNumber}'/>
                                                        <button type='submit' class='btn btn-danger'>Save changes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>";

                                    // Remove table modal
                                    echo "
                                    <div class='modal fade' id='removeTable{$table->id}Modal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' style='text-align: left;'>
                                        <div class='modal-dialog' role='document'>
                                            <div class='modal-content' style='height: 70%'>
                                                <div class='modal-header'>
                                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                                                    <h4 class='modal-title' id='myModalLabel'>Remove Restaurant Table</h4>
                                                </div>
                                                <div class='modal-body'>
                                                    <p>Really remove restaurant table number {$table->tableNumber}?</p>
                                                    <p><strong>Warning</strong>: This operation cannot be undone. Changing hours and table information affects the ability to take
                                                        future table reservations, but does not automatically cancel reservations already in the system.</p>
                                                </div>
                                                <div class='modal-footer'>
                                                    <form role='form' enctype='multipart/form-data' role='remove_table' id='remove_table_form' method='post' action='index.php?controller=BusinessSettings&action=removeTable'>
                                                        <button type='button' class='btn btn-default' data-dismiss='modal'>Do not remove</button>
                                                        <input type='hidden' name='id' value='{$table->id}'/>
                                                        <button type='submit' class='btn btn-danger'>Remove</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>";
                                }
                            } else {
                                echo "<p>No tables are assigned to this restaurant. Add some using the buttons below.</p>";
                            }
                            ?>
                        </table>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <a class="btn btn-primary" data-toggle='modal' data-target='#addTableModal'><i class="glyphicon glyphicon-plus"></i>&nbsp;&nbsp;Add table ...</a>
                                <a class="btn btn-primary" data-toggle='modal' data-target='#addTablesModal'><i class="glyphicon glyphicon-plus"></i>&nbsp;&nbsp;Add consecutive tables ...</a>
                            </div>
                        </div>

                        <?php
                        if (isset($removeTableSuccess)) {
                            if ($removeTableSuccess) {
                                echo "<p>Table was successfully removed.</p>";
                            } else {
                                echo "<p>There was an error removing table.</p>";
                            }
                        }

                        if (isset($addTableSuccess)) {
                            if ($addTableSuccess) {
                                echo "<p>Table(s) successfully added.</p>";
                            } else {
                                echo "<p>There was an error adding table(s). Ensure table number(s) don't conflict with existing tables.</p>";
                            }
                        }

                        if (isset($editTableSuccess)) {
                            if ($editTableSuccess) {
                                echo "<p>Table(s) successfully updated.</p>";
                            } else {
                                echo "<p>There was an error updating table. Try again.</p>";
                            }
                        }
                        ?>
                    </div>



                </div>
            </div>
        </div>

        <!-- Add hours range modal -->
        <div class="modal fade" id="addHoursRangeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form class="form-horizontal" enctype='multipart/form-data' role='additem' id='add_range_form' method='post' action='index.php?controller=BusinessSettings&action=addRange'>
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Add hours range</h4>
                        </div>

                        <div class="modal-body" style="min-height: 450px;">
                            <p>Add a new hours range for a specified day.</p>
                            <p><strong>Restrictions</strong>: It is allowable to add multiple hour ranges per day; ensure these ranges do not overlap. Hours extending to or past midnight will be split into the next day in a future update; for now, this must be done manually -- i.e. the range should end, at the latest, at 11:59pm. Ensure the end time is later than the start time.</p>
                            <p></p>
                            <input type='hidden' class='form-control' name='restaurant_id' value="<?php echo $this->restaurant->id; ?>">
                            <div class="form-group">
                                <label for="day" class="col-sm-3 control-label">Day of the week</label>
                                <div class="col-sm-8">
                                    <select class="form-control" id="day" name="day">
                                        <option selected="selected" value="0">Sunday</option>
                                        <option value="1">Monday</option>
                                        <option value="2">Tuesday</option>
                                        <option value="3">Wednesday</option>
                                        <option value="4">Thursday</option>
                                        <option value="5">Friday</option>
                                        <option value="6">Saturday</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="seating_capacity" class="col-sm-3 control-label">Start time</label>
                                <div class="col-sm-3">
                                    <!-- start datetimepicker -->
                                    <div class="form-group">
                                        <div class='input-group date' id='datetimepicker_addstart'>
                                            <input type='text' name="start" class="form-control" value="10:00 AM" required pattern="[0-9]{1,2}:[0-9]{2} [AaPp][Mm]" title="e.g. 10:30 PM, 07:30 am, or 9:45 PM" />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-time"></span>
                                            </span>
                                        </div>
                                    </div>
                                    <!-- end datetimepicker -->
                                </div>
                                <label for="seating_capacity" class="col-sm-2 control-label">End time</label>
                                <div class="col-sm-3">
                                    <!-- start datetimepicker -->
                                    <div class="form-group">
                                        <div class='input-group date' id='datetimepicker_addend'>
                                            <input type='text' name="end" class="form-control" value="10:00 PM" required pattern="[0-9]{1,2}:[0-9]{2} [AaPp][Mm]" title="e.g. 10:30 PM, 07:30 am, or 9:45 PM" />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-time"></span>
                                            </span>
                                        </div>
                                    </div>
                                    <!-- end datetimepicker -->
                                </div>
                            </div>

                            <!--                        <div class="form-group">
                                                        <label for="email" class="col-sm-3 control-label">Features</label>
                                                        <div class="col-sm-8">
                                                            <textarea class='form-control' rows='3' name='features'></textarea>
                                                        </div>
                                                    </div>-->

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type='submit' class='btn btn-primary'>Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- end Add hours range modal -->

        <!-- Add table modal -->
        <div class="modal fade" id="addTableModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form class="form-horizontal" enctype='multipart/form-data' role='additem' id='add_table_form' method='post' action='index.php?controller=BusinessSettings&action=addTable'>

                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Add restaurant table</h4>
                        </div>

                        <div class="modal-body">
                            <input type='hidden' class='form-control' name='restaurant_id' value="<?php echo $this->restaurant->id; ?>">
                            <div class="form-group">
                                <label for="table_number" class="col-sm-3 control-label">Table number</label>
                                <div class="col-sm-8">
                                    <?php
                                    // Get the next smallest available table number.
                                    if ($this->tables) {
                                        $nextSmallestTableNo = 0;
                                        foreach ($this->tables as $index => $table) {
                                            if ($table->tableNumber == ($nextSmallestTableNo + 1)) {
                                                // Iterate if consecutive table.
                                                $nextSmallestTableNo++;
                                                if ($index == end(array_keys($this->tables))) {
                                                    // We've reached the end of the array. Next available
                                                    // table is the next potential index.
                                                    $nextSmallestTableNo++;
                                                }
                                            } else {
                                                // If not consecutive, we've found a gap. That is
                                                // the next available smallest table.
                                                $nextSmallestTableNo++;
                                                break;
                                            }
                                        }
                                    } else {
                                        $nextSmallestTableNo = 1;
                                    }
                                    ?>
                                    <input type='number' min='1' max='99' size='2' class='form-control' name='table_number' value='<?php echo $nextSmallestTableNo; ?>'></input>
                                    Valid range is 1-99. The smallest available table number has been filled in for you.
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="seating_capacity" class="col-sm-3 control-label">Seating Capacity</label>
                                <div class="col-sm-8">
                                    <input type='number' min='1' max='12' size='2' class='form-control' name='seating_capacity' placeholder='' value="1">
                                    Valid range is 1-12.
                                </div>
                            </div>

                            <!--                        <div class="form-group">
                                                        <label for="email" class="col-sm-3 control-label">Features</label>
                                                        <div class="col-sm-8">
                                                            <textarea class='form-control' rows='3' name='features'></textarea>
                                                        </div>
                                                    </div>-->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type='submit' class='btn btn-primary'>Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- end Add table modal -->

        <!-- Add multiple tables modal -->
        <div class="modal fade" id="addTablesModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form class="form-horizontal" enctype='multipart/form-data' role='additem' id='add_tables_form' method='post' action='index.php?controller=BusinessSettings&action=addTables'>

                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Add consecutive tables</h4>
                        </div>

                        <div class="modal-body">
                            <input type='hidden' class='form-control' name='restaurant_id' value="<?php echo $this->restaurant->id; ?>">
                            <p>Add a number of consecutive tables beginning with the specified table number and having the specified seating capacity.</p>
                            <p></p>
                            <div class="form-group">
                                <label for="table_number" class="col-sm-3 control-label">Table number</label>
                                <div class="col-sm-8">
                                    <?php
                                    if ($this->tables) {
                                        $maxTableNo = end($this->tables);
                                        $maxTableNo = $maxTableNo->tableNumber;
                                    } else {
                                        $maxTableNo = 0;
                                    }
                                    
                                    ?>
                                    <input disabled="disabled" min='<?php echo $maxTableNo + 1 ?>' max='99' size='2' class='form-control' value='<?php echo $maxTableNo + 1 ?>'></input>
                                    The next table number in sequence has been filled in for you.
                                    <input type="hidden" name="table_number" value='<?php echo $maxTableNo + 1 ?>'> <!-- Actual value for table_number that gets sent -->
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="seating_capacity" class="col-sm-3 control-label">Seating Capacity</label>
                                <div class="col-sm-8">
                                    <input type='number' min='1' max='12' size='2' class='form-control' name='seating_capacity' placeholder='' value="1">
                                    Valid range is 1-12.
                                </div>
                            </div>

                            <!--                        <div class="form-group">
                                                        <label for="email" class="col-sm-3 control-label">Features</label>
                                                        <div class="col-sm-8">
                                                            <textarea class='form-control' rows='3' name='features'></textarea>
                                                        </div>
                                                    </div>-->

                            <div class="form-group">
                                <label for="num_consecutive" class="col-sm-3 control-label">Number of consecutive tables</label>
                                <div class="col-sm-8">
                                    <input type='number' min='1' max='<?php echo 98 - $maxTableNo ?>' size='2' class='form-control' name='num_consecutive' placeholder='' value="1">
                                    Max is <?php echo 98 - $maxTableNo ?>.
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
        <!-- end Add table modal -->


        <!-- Footer -->
        <?php include_once('view/module/footer.php'); ?>

        <!-- jQuery Version 1.11.0 -->
        <script type="text/javascript" src="js/jquery-1.11.0.js"></script>

        <!-- depencencies for Datetimepicker -->
        <script type="text/javascript" src="js/moment.min.js"></script>
        <script type="text/javascript" src="js/transition.js"></script>
        <script type="text/javascript" src="js/collapse.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script type="text/javascript" src="js/bootstrap.min.js"></script>

        <!-- Bootstrap Datetimepicker -->
        <script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>


        <script type="text/javascript">
            $('#datetimepicker_addstart').datetimepicker({
                format: 'h:mm A', // e.g. 7:00 PM
                stepping: 1       // 1 minute intervals
            });
            $('#datetimepicker_addend').datetimepicker({
                format: 'h:mm A', // e.g. 7:00 PM
                stepping: 1       // 1 minute intervals
            });
<?php
foreach ($this->hours as $range) {
    echo "
            $('#dtp_editstart{$range->id}').datetimepicker({
                format: 'h:mm A', // e.g. 7:00 PM
                stepping: 1       // 1 minute intervals
            });
            $('#dtp_editend{$range->id}').datetimepicker({
                format: 'h:mm A', // e.g. 7:00 PM
                stepping: 1       // 1 minute intervals
            });
                            ";
}
?>
        </script>
    </body>
</html>
