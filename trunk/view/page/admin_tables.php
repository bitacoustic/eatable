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

                    <!-- Report status of add/remove operations, if applicable -->
                    <p>
                        <?php
                        if (isset($removedIdSuccess)) {
                            echo "Removal of restaurant table record <strong>{$removedTable->id}</strong> successful.";
                        } elseif (isset($removedIdSuccess) && !$removedIdSuccess) {
                            echo "Restaurant table record <strong>{$removedTable->id}</strong> was not removed.";
                        }

                        if (isset($addedSuccess)) {
                            if ($addedSuccess == 0) {
                                $addedSuccess = "The new table was not added to the database.";
                            } else {
                                $addedSuccess = "The new table was added to the database.";
                            }
                        }
                        ?>
                    </p>

                    <!-- Button to open "Manual add table" modal -->
                    <a class="btn btn-default" data-toggle="modal" data-target="#manualAddModal"><i class="glyphicon glyphicon-plus-sign"></i>&nbsp;&nbsp;Manual add...</a>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><a href="index.php?controller=AdminTables&action=invoke&sort=id">UID</a></th>
                                <th><a href="index.php?controller=AdminTables&action=invoke&sort=restaurant_id">Restaurant (UID)</a></th>
                                <th><a href="index.php?controller=AdminTables&action=invoke&sort=table_number">Table Number</a></th>
                                <th><a href="index.php?controller=AdminTables&action=invoke&sort=seating_capacity">Seating Capacity</a></th>
                                <th><a href="index.php?controller=AdminTables&action=invoke&sort=features">Features</a></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($tables) {
                                foreach ($tables as $table) {
                                    $restaurant = Restaurant::getRestaurantById($table->restaurantId);

                                    echo "<tr>\n";
                                    echo "    <td>" . $table->id . "</td>\n";
                                    echo "    <td>" . $restaurant->name . " (" . $table->restaurantId . ")</td>\n";
                                    echo "    <td>" . $table->tableNumber . "</td>\n";
                                    echo "    <td>" . $table->seatingCapacity . "</td>\n";
                                    echo "    <td>" . $table->features . "</td>\n";
                                    echo "    <td><a data-toggle='modal' data-target='#removeTable{$table->id}Modal'><i class='glyphicon glyphicon-remove'></i></a></td>";
                                    echo "</tr>\n";

                                    // Remove table modal
                                    echo "
                                    <div class='modal fade' id='removeTable{$table->id}Modal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
                                        <div class='modal-dialog' role='document'>
                                            <div class='modal-content' style='height: 70%'>
                                                <div class='modal-header'>
                                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                                                    <h4 class='modal-title' id='myModalLabel'>Remove Restaurant Table</h4>
                                                    <p><strong>Warning</strong>: This operation cannot be undone.</p>
                                                </div>
                                                <div class='modal-body'>
                                                    Really remove restaurant table with UID {$table->id} (table number {$table->tableNumber} at restaurant {$restaurant->name} with UID {$restaurant->id})?
                                                </div>
                                                <div class='modal-footer'>
                                                    <button type='button' class='btn btn-default' data-dismiss='modal'>Do not remove</button>
                                                    <a class='btn btn-danger' href='index.php?controller=AdminTables&action=removeTable&id={$table->id}'>Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>";
                                }
                            } else {
                                echo "<tr><td colspan=9>No table information is available.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <?php include_once('view/module/footer.php'); ?>

        <!-- Manual add table modal -->
        <div class="modal fade" id="manualAddModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form class="form-horizontal" enctype='multipart/form-data' role='additem' id='add_restaurant_form' method='post' action='index.php?controller=AdminTables&action=addTable'>

                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Manual Add Restaurant Table</h4>
                            <p><strong>Warning</strong>: For testing purposes only. Form has minimal validation.</p>
                        </div>

                        <div class="modal-body">
                            <div class="form-group">
                                <label for="email" class="col-sm-3 control-label">Restaurant UID</label>
                                <div class="col-sm-8">
                                    <input type='number' min='1' size='8' class='form-control' name='restaurant_id' placeholder=''>
                                    Ensure restaurant ID is valid or the table will not be added.
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-sm-3 control-label">Table number</label>
                                <div class="col-sm-8">
                                    <input type='number' min='1' size='2' class='form-control' name='table_number' placeholder=''>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-sm-3 control-label">Seating Capacity</label>
                                <div class="col-sm-8">
                                    <input type='number' min='1' max='12' size='2' class='form-control' name='seating_capacity' placeholder=''>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-sm-3 control-label">Features</label>
                                <div class="col-sm-8">
                                    <textarea class='form-control' rows='3' name='features'></textarea>
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
