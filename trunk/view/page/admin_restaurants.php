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
        <?php include_once('view/module/navigation.php') ?>

        <!-- Page Content -->
        <div class="container">
            <?php include_once('view/module/admin_tabs.php') ?>
            <div class="row">
                <div class="col-md-12">
                    <p><strong>Tip</strong>: To edit a restaurant, assign yourself as an owner of that restaurant via the Users tab. You will be invisible to the actual owner.</p>
                    
                    <!-- Filter by -->
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-12">Filter by <strong>status</strong></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <a class="btn btn-sm btn-default btn-block
                                <?php
                                if (isset($filterProperty) && strcmp($filterProperty, 'approved') != NIL) {
                                    echo ' active';
                                }
                                ?>" href="index.php?controller=Admin&action=invoke">All</a>
                            </div>
                            <div class="col-md-4">
                                <a class="btn btn-sm btn-default btn-block
                                <?php
                                if (isset($filterProperty) && strcmp($filterProperty, 'approved') == 0 && isset($filterValue) && $filterValue != 1) {
                                    echo ' active';
                                }
                                ?>"
                                   href="index.php?controller=Admin&action=invoke&filterProperty=approved&filterValue=NULL">Pending</a>
                            </div>
                            <div class="col-md-4">
                                <a class="btn btn-sm btn-default btn-block
                                <?php
                                if (isset($filterValue) && $filterValue == 1) {
                                    echo ' active';
                                }
                                ?>
                                   " href="index.php?controller=Admin&action=invoke&filterProperty=approved&filterValue=1&filterFlagNumber=true">Approved</a>
                            </div>
                        </div>
                    </div>

                    <!-- Report status of remove operation, if applicable -->
                    <p>
                        <?php
                        if (isset($removedId)) {
                            echo "Removal of restaurant record <strong>{$removedId}</strong> successful.";
                        } elseif (isset($removedIdSuccess) && !$removedIdSuccess) {
                            echo "Restaurant record <strong>{$removedId}</strong> was not removed.";
                        }

                        if (isset($addedSuccess)) {
                            echo $addedSuccess;
                        }
                        ?>
                    </p>
                    
                    <!-- Button to open "Manual add restaurant" modal -->
                    <a class="btn btn-default" data-toggle="modal" data-target="#manualAddModal"><i class="glyphicon glyphicon-plus-sign"></i>&nbsp;&nbsp;Manual add...</a>
                    
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><a href="index.php?controller=Admin&action=invoke&sort=id">UID</a></th>
                                <th><a href="index.php?controller=Admin&action=invoke&sort=approved">Status</a></th>
                                <!--<th><a href="index.php?controller=Admin&action=invoke&sort=owner">Business Owner</a>Business Owner</th>-->
                                <th><a href="index.php?controller=Admin&action=invoke&sort=name">Name</a></th>
                                <th><a href="index.php?controller=Admin&action=invoke&sort=address_street">Address</a></th>
                                <th><a href="index.php?controller=Admin&action=invoke&sort=address_city">City</a></th>
                                <th><a href="index.php?controller=Admin&action=invoke&sort=address_state">State</a></th>
                                <th><a href="index.php?controller=Admin&action=invoke&sort=address_postal_code">Postal Code</a></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($restaurants) {
                                foreach ($restaurants as $restaurant) {
                                    echo "<tr>\n";
                                    echo "    <td>" . $restaurant->id . "</td>\n";
                                    echo "    <td>" . ($restaurant->approved ? "Approved" : "Pending") . "&nbsp;&nbsp;<a data-toggle='modal' data-target='#toggleStatusOf{$restaurant->id}Modal'><i class='glyphicon glyphicon-pencil'></i></a></td>\n";
//                                    echo "    <td>" . $restaurant->owner . "</td>\n";
                                    echo '    <td><a href="index.php?controller=Restaurant&action=invoke&restaurant-id=' . $restaurant->id . '&partysizesearch=4&selecteddatesearch=' . date("m-d-Y") . '&selectedtimesearch=7%3A00+PM">' . $restaurant->name . "</a></td>\n";
                                    echo "    <td>" . $restaurant->addressStreet . "</td>\n";
                                    echo "    <td>" . $restaurant->addressCity . "</td>\n";
                                    echo "    <td>" . $restaurant->addressState . "</td>\n";
                                    echo "    <td>" . $restaurant->addressPostalCode . "</td>\n";
                                    echo "    <td><a data-toggle='modal' data-target='#removeRestaurant{$restaurant->id}Modal'><i class='glyphicon glyphicon-remove'></i></a></td>";
                                    echo "</tr>\n";

                                    // Toggle status modal
                                    echo "
                                    <div class='modal fade' id='toggleStatusOf{$restaurant->id}Modal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
                                        <div class='modal-dialog' role='document'>
                                            <div class='modal-content' style='height: 70%'>
                                                <div class='modal-header'>
                                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                                                    <h4 class='modal-title' id='myModalLabel'>Set restaurant status</h4>
                                                </div>
                                                <div class='modal-body'>";
                                    if ($restaurant->approved) {
                                        echo "Change status of restaurant {$restaurant->id} ({$restaurant->name}, {$restaurant->addressStreet}, {$restaurant->addressCity}, {$restaurant->addressState} {$restaurant->addressPostalCode}) to <strong>pending</strong>? This hides the restaurant from public view and no reservations at this restaurant can be placed.";
                                    } else {
                                        echo "Change status of restaurant {$restaurant->id} ({$restaurant->name}, {$restaurant->addressStreet}, {$restaurant->addressCity}, {$restaurant->addressState} {$restaurant->addressPostalCode}) to <strong>approved</strong>? This makes the restaurant visible to the public, and new reservations can be placed.";
                                    }
                                    echo "
                                                </div>
                                                <div class='modal-footer'>
                                                    <button type='button' class='btn btn-default' data-dismiss='modal'>No</button>";
                                    if ($restaurant->approved) {
                                        echo "
                                            <a class='btn btn-warning' href='index.php?controller=Admin&action=setStatus&id={$restaurant->id}&status=0'>Yes</a>
                                            ";
                                    } else {
                                        echo "
                                            <a class='btn btn-warning' href='index.php?controller=Admin&action=setStatus&id={$restaurant->id}&status=1'>Yes</a>
                                            ";
                                    }
                                    echo "
                                                </div>
                                            </div>
                                        </div>
                                    </div>";

                                    // Remove restaurant modal
                                    echo "
                                    <div class='modal fade' id='removeRestaurant{$restaurant->id}Modal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
                                        <div class='modal-dialog' role='document'>
                                            <div class='modal-content' style='height: 70%'>
                                                <div class='modal-header'>
                                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                                                    <h4 class='modal-title' id='myModalLabel'>Remove Restaurant</h4>
                                                    <p><strong>Warning</strong>: This operation cannot be undone.</p>
                                                </div>
                                                <div class='modal-body'>
                                                    Really remove restaurant record {$restaurant->id} ({$restaurant->name}, {$restaurant->addressStreet}, {$restaurant->addressCity}, {$restaurant->addressState} {$restaurant->addressPostalCode})?
                                                </div>
                                                <div class='modal-footer'>
                                                    <button type='button' class='btn btn-default' data-dismiss='modal'>Do not remove</button>
                                                    <a class='btn btn-danger' href='index.php?controller=Admin&action=removeRestaurant&id={$restaurant->id}'>Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>";
                                }
                            } else {
                                echo "<tr><td colspan=9>No restaurant information is available.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <?php include_once('view/module/footer.php'); ?>

        <!-- Manual add restaurant modal -->
        <div class="modal fade" id="manualAddModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form class="form-horizontal" enctype='multipart/form-data' role='additem' id='add_restaurant_form' method='post' action='index.php?controller=Admin&action=addRestaurant'>

                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Manual Add Restaurant</h4>
                            <p><strong>Warning</strong>: For testing purposes only. Form has minimal validation.</p>
                        </div>

                        <div class="modal-body">
                            <div class="form-group">
                                <label for="email" class="col-sm-3 control-label">Name</label>
                                <div class="col-sm-8">
                                    <input type='text' class='form-control' name='name' id='name' placeholder=''>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-sm-3 control-label">Street</label>
                                <div class="col-sm-8">
                                    <input type='text' class='form-control' name='address_street' id='address_street' placeholder='1 Market St'>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-sm-3 control-label">City</label>
                                <div class="col-sm-8">
                                    <input type='text' class='form-control' name='address_city' id='address_city' placeholder='San Francisco'>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-sm-3 control-label">State</label>
                                <div class="col-sm-8">
                                    <input type='text' size='2' class='form-control' name='address_state' id='address_state' placeholder='CA'>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-sm-3 control-label">Postal Code</label>
                                <div class="col-sm-8">
                                    <input class='form-control' name='address_postal_code' id='address_postal_code' placeholder='94108'>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-sm-3 control-label">Phone Number</label>
                                <div class="col-sm-8">
                                    <input type='tel' class='form-control' name='phone_number' id='phone_number' placeholder='415-555-1234'>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-sm-3 control-label">Cuisine Type</label>
                                <div class="col-sm-8">
                                    <input type='text' class='form-control' name='cuisine_type' id='cuisine_type' placeholder=''>
                                </div>
                            </div>


                            <div class="form-group">
                                <label for="email" class="col-sm-3 control-label">Keywords</label>
                                <div class="col-sm-8">
                                    <input class='form-control' name='keywords' id='keywords' placeholder='burgers,italian,sushi'>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-sm-3 control-label">Image</label>
                                <div class="col-sm-8">
                                    <input type='file' name='newimage' id='newimage' class='form-control' style='border: 0'>
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

        <!-- jQuery Version 1.11.0 -->
        <script src="js/jquery-1.11.0.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>
