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

    </head>

    <body>
        <!-- Navigation bar -->
        <?php include_once('view/module/navigation.php') ?>	

        <!-- Page Content -->
        <div class="container">
            <?php include_once('view/module/business_tabs.php') ?>

            <div class="row">
                <div class="col-md-12">
                    <?php
                    if ($this->menu) {
                        foreach ($this->menu as $menuItem) {
                            echo "
                            <div class='row'>
                                <div class='col-md-12'>
                                    <div class='boxedform'>
                                        <div class='row'>
                                            <div class='col-md-8'>
                                                <strong>Name</strong>: $menuItem->name<br/>
                                                <strong>Price</strong>: \${$menuItem->price}<br/>
                                                <strong>Description</strong>: $menuItem->description
                                            </div>
                                            <div class='col-md-4'>
                                                <i class='glyphicon glyphicon-pencil'></i>&nbsp;Edit<br/>
                                                <a data-toggle='modal' data-target='#removeMenuItem{$menuItem->id}Modal'><i class='glyphicon glyphicon-remove'></i>&nbsp;Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            ";
                                                
                            // Remove menu item modal
                            echo "
                            <div class='modal fade' id='removeMenuItem{$menuItem->id}Modal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' style='text-align: left;'>
                                <div class='modal-dialog' role='document'>
                                    <div class='modal-content' style='height: 70%'>
                                        <div class='modal-header'>
                                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                                            <h4 class='modal-title' id='myModalLabel'>Remove Menu Item</h4>
                                        </div>
                                        <div class='modal-body'>
                                            <p>Really remove menu item \"{$menuItem->name}\"?</p>
                                            <p><strong>Warning</strong>: This operation cannot be undone. Removing a menu item does not notify any customers who may have ordered
                                            this item as part of a reservation that the item is no longer available.</p>
                                        </div>
                                        <div class='modal-footer'>
                                            <form role='form' enctype='multipart/form-data' role='remove_table' id='remove_table_form' method='post' action='index.php?controller=BusinessMenu&action=removeMenuItem'>
                                                <button type='button' class='btn btn-default' data-dismiss='modal'>Do not remove</button>
                                                <input type='hidden' name='id' value='{$menuItem->id}'/>
                                                <button type='submit' class='btn btn-danger'>Remove</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>";
                        }
                    } else {
                        echo "<p>No menu items found for this restaurant. Use the button <code>Add menu item...</code> below to start building your menu.</p>";
                    }
                    ?>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <a class="btn btn-primary" data-toggle='modal' data-target='#addMenuItemModal'><i class="glyphicon glyphicon-plus"></i>&nbsp;&nbsp;Add menu item ...</a>
                </div>
            </div>

        </div>
        
        <!-- Add menu item modal -->
        <div class="modal fade" id="addMenuItemModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form class="form-horizontal" enctype='multipart/form-data' role='additem' id='add_table_form' method='post' action='index.php?controller=BusinessMenu&action=addMenuItem'>

                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Add menu item</h4>
                        </div>

                        <div class="modal-body">
                            <input type='hidden' class='form-control' name='restaurant_id' value="<?php echo $this->restaurant->id; ?>">
                            
                            <div class="form-group">
                                <label for="name" class="col-sm-3 control-label">Name</label>
                                <div class="input-group col-sm-8">
                                    <input type='text' class='form-control' name='name' placeholder='' required />
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="price" class="col-sm-3 control-label">Price (USD)</label>
                                <div class="input-group col-sm-8">
                                    <span class="input-group-addon" id="basic-addon1">$</span>
                                    <input type='number' min='0.01' step="0.01" class='form-control' name='price' value='' required />
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="itemdescription" class="col-sm-3 control-label">Description</label>
                                <div class="input-group col-sm-8">
                                    <textarea class='form-control' rows='5' name='itemdescription' required></textarea>
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
        <!-- End add menu item modal -->

        <!-- Footer -->
        <?php include_once('view/module/footer.php'); ?>

        <!-- jQuery Version 1.11.0 -->
        <script src="js/jquery-1.11.0.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>
