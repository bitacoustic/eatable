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
                    <!-- Report status of remove operation, if applicable -->
                    <p>
                        <?php
                        if (isset($removedId)) {
                            echo "Removal of user record <strong>{$removedId}</strong> successful.";
                        } elseif (isset($removedIdSuccess) && !$removedIdSuccess) {
                            echo "User record <strong>{$removedId}</strong> was not removed.";
                        }
                        ?>
                    </p>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><a href="index.php?controller=AdminUsers&action=invoke&sort=id">UID</a></th>
                                <th><a href="index.php?controller=AdminUsers&action=invoke&sort=fname">First</a></th>
                                <th><a href="index.php?controller=AdminUsers&action=invoke&sort=lname">Last</a></th>
                                <th><a href="index.php?controller=AdminUsers&action=invoke&sort=email">Email</a></th>
                                <th>Session ID</th>
                                <th>Roles</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($users) {
                                foreach ($users as $user) {
                                    echo "<tr>\n";
                                    echo "    <td>" . $user->id . "</td>\n";
                                    echo "    <td>" . $user->fname . "</td>\n";
                                    echo "    <td>" . $user->lname . "</td>\n";
                                    echo "    <td>" . $user->email . "</td>\n";
                                    echo "    <td style='font-size: 0.7em;'>" . $user->session_id . "</td>\n";
                                    echo '    <td>';

                                    $checkEmployee = Employee::getEmployeeById($user->id);
                                    $isEmployee = is_object($checkEmployee);
                                    if ($isEmployee) {
                                        echo "Employee(Restaurant{$checkEmployee->restaurantId})";

                                        $checkOwner = Owner::getOwnerById($user->id);
                                        $isOwner = is_object($checkOwner);
                                        if ($isOwner) {
                                            echo ":Owner";
                                        }

                                        $checkHost = Host::getHostById($user->id);
                                        $isHost = is_object($checkHost);
                                        if ($isHost) {
                                            echo ":Host";
                                        }

                                        echo "&nbsp;&nbsp;";
                                    }

                                    $checkAdmin = Administrator::getAdminByUserId($user->id);
                                    $isAdmin = is_object($checkAdmin);
                                    if ($isAdmin) {
                                        echo "Admin &nbsp;&nbsp;";
                                    }

                                    echo "<a data-toggle='modal' data-target='#editUser{$user->id}RolesModal'><i class='glyphicon glyphicon-pencil'></i></a></td>";

                                    echo "<!-- Edit user {$user->id} roles modal -->
                                    <div class='modal fade' id='editUser{$user->id}RolesModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
                                        <div class='modal-dialog' role='document'>
                                            <div class='modal-content' style='height: 70%'>
                                                <form class='form-horizontal' role='update_user_permissions' id='updatepermissionsform' method='post' action='index.php?controller=AdminUsers&action=updatePermissions'>
                                                    <div class='modal-header'>
                                                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                                                        <h4 class='modal-title' id='myModalLabel'>Change user permissions</h4>
                                                    </div>
                                                    <div class='modal-body'>                                                 
                                                        <div class='well'>
                                                            <div class='checkbox'>
                                                                <label><input type='checkbox' id='checkbox_employee{$user->id}' name='roles[]' value='employee'" . ($isEmployee ? " checked" : "") . ">Employee</label> for restaurant
                                                                    <input type='number' min='1' id='input_employee{$user->id}' name='restaurant_id' required aria-required='true' pattern='[0-9]+' title='Number' value='" 
                                                                    . (is_object($checkEmployee) ? $checkEmployee->restaurantId : "") . "' disabled>
                                                            </div>

                                                            <div class='checkbox'>
                                                                <label><input type='checkbox' id='checkbox_owner{$user->id}' name='roles[]' value='owner' " . ($isOwner ? " checked" : "") . ">Owner</label>    
                                                            </div>
                                                            <div class='checkbox'>
                                                                <label><input type='checkbox' id='checkbox_host{$user->id}' name='roles[]' value='host'" . ($isHost ? " checked" : "") . ">Host</label>
                                                            </div>
                                                        </div>

                                                        <div class='well'>
                                                            <div class='checkbox'>
                                                                <label><input type='checkbox' id='checkbox_admin{$user->id}' name='roles[]' value='admin' " . ($isAdmin ? "checked" : "") . ">Administrator</label>    
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class='modal-footer'>
                                                        <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
                                                        <input type='hidden' name='id' value='{$user->id}' />
                                                        <button type='submit' class='btn btn-warning'>Save changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>";

                                    echo "    <td><a data-toggle='modal' data-target='#removeUser{$user->id}Modal'><i class='glyphicon glyphicon-remove'></i></a></td>";
                                    echo "</tr>\n";

                                    echo "<!-- Remove user {$user->id} modal -->
                                    <div class='modal fade' id='removeUser{$user->id}Modal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
                                        <div class='modal-dialog' role='document'>
                                            <div class='modal-content' style='height: 70%'>
                                                <div class='modal-header'>
                                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                                                    <h4 class='modal-title' id='myModalLabel'>Remove User</h4>
                                                    <p><strong>Warning</strong>: This operation cannot be undone.</p>
                                                </div>
                                                <div class='modal-body'>
                                                    Really remove user record {$user->id} ({$user->fname} {$user->lname}, {$user->email})?
                                                </div>
                                                <div class='modal-footer'>
                                                    <button type='button' class='btn btn-default' data-dismiss='modal'>Do not remove</button>
                                                    <a class='btn btn-danger' href='index.php?controller=AdminUsers&action=removeUser&id={$user->id}'>Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>";
                                }
                            } else {
                                echo "<tr><td colspan=9>No user information is available.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
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
            $(document).ready(function () {
<?php
foreach ($users as $user) {
    echo "
                $('#checkbox_employee{$user->id}').change(function () {
                    if ($(this).is(':checked')) {
                        $('#input_employee{$user->id}').removeAttr('disabled');
                        $('#checkbox_owner{$user->id}').removeAttr('disabled');
                        $('#checkbox_host{$user->id}').removeAttr('disabled');       

                    } else {
                        $('#input_employee{$user->id}').attr('disabled', 'disabled');
                        $('#checkbox_owner{$user->id}').attr('disabled', 'disabled');
                        $('#checkbox_host{$user->id}').attr('disabled', 'disabled');
                        $('#checkbox_owner{$user->id}').removeAttr('checked');
                        $('#checkbox_host{$user->id}').removeAttr('checked');       
                    }
                });
            ";
}
?>
            });
        </script>

    </body>
</html>
