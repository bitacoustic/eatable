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
                <div class='col-md-12'>
                    <h2>Hosts for <?php echo $this->restaurant->name; ?></h2>

                    <a class='btn btn-primary' data-toggle='modal' data-target='#addHostModal'>
                        <i class='glyphicon glyphicon-plus'></i>&nbsp;&nbsp;Add host ...
                    </a>

                    <table class="table table-striped boxedform">
                        <tr>
                            <th style='border-top: 1px solid #37392c;'>Role</th>
                            <th style='border-top: 1px solid #37392c;'>Username</th>
                            <th style='border-top: 1px solid #37392c;'>Name</th>
                            <th style='border-top: 1px solid #37392c;'></th>
                        </tr>
                            <?php
                            if ($this->hosts) {
                                foreach ($this->hosts as $host) {
                                    $user = User::getUserById($host->id);
                                    if (!$user->isAdmin()) {
                                        // Display role
                                        echo "<tr>
                                <td>";

                                        $isOwner = $user->isOwner();
                                        if ($isOwner) {
                                            echo "Business owner";
                                        } else {
                                            echo "Host";
                                        }

                                        echo "</td>
                                    <td>$user->email</td>
                                    <td>$user->fname $user->lname</td>
                                    <td>";


                                        if (!$isOwner) {
                                            echo "<a data-toggle='modal' data-target='#removeHost{$host->id}Modal'><i class='glyphicon glyphicon-remove'></i>&nbsp;Delete</a> &nbsp; ";
                                        }
                                        echo "</td>
                                </tr>";

                                        // Remove table modal
                                        echo "
                                <div class='modal fade' id='removeHost{$host->id}Modal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' style='text-align: left;'>
                                    <div class='modal-dialog' role='document'>
                                        <div class='modal-content' style='height: 70%'>
                                            <div class='modal-header'>
                                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                                                <h4 class='modal-title' id='myModalLabel'>Delete Restaurant Host Account</h4>
                                            </div>
                                            <div class='modal-body'>
                                                <p>Really delete host account for $host->fname $host->lname ($host->email)?</p>
                                                <p><strong>Warning</strong>: This operation cannot be undone.</p>
                                            </div>
                                            <div class='modal-footer'>
                                                <form role='form' enctype='multipart/form-data' role='remove_host' id='remove_host_form' method='post' action='index.php?controller=BusinessHosts&action=removeHost'>
                                                    <button type='button' class='btn btn-default' data-dismiss='modal'>Do not remove</button>
                                                    <input type='hidden' name='id' value='{$host->id}'/>
                                                    <button type='submit' class='btn btn-danger'>Remove</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>";
                                    }
                                }
                            } else {
                                echo "<tr><td colspan='4'>No hosts are assigned to this restaurant.</td></tr>";
                            }
                            ?>
                    </table>
                </div>
            </div>

        </div>

        <!-- Add host modal -->
        <div class="modal fade" id="addHostModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form class="form-horizontal" enctype='multipart/form-data' role='add_host_form' id='addhost_form' name="addhost_form" method='post' action='index.php?controller=BusinessHosts&action=addHost' onsubmit="return validateRegistrationForm()">

                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Add restaurant host</h4>
                        </div>

                        <div class="modal-body">
                            <input type='hidden' class='form-control' name='restaurant_id' value="<?php echo $this->restaurant->id; ?>">

                            <div class="form-group">
                                <label for="fname" class="col-xs-3 control-label">First name</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="fname" name="fname" aria-describedby="fname-format" required aria-required="true" pattern="[A-Za-z-']+" title="Valid characters are A-Z a-z - '">
                                    <span id="fname-format" class="help">Format: Valid characters are A-Z a-z - '</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="lname" class="col-sm-3 control-label">Last name</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="lname" name="lname" aria-describedby="fname-format" required aria-required="true" pattern="[A-Za-z-']+" title="Valid characters are A-Z a-z - '">
                                    <span id="lname-format" class="help">Format: Valid characters are A-Z a-z - '</span>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="form-group">
                                <label for="email" class="col-sm-3 control-label">Email address</label>
                                <div class="col-sm-8">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="user@example.com" required aria-required="true">
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="form-group">
                                <label for="password" class="col-sm-3 control-label">Password</label>
                                <div class="col-sm-8">
                                    <input type="password" class="form-control" id="password" name="password" required aria-required="true" pattern=".{5,}" title="5 or more characters">
                                    <span id="pass-format" class="help">Choose a password of 5 or more characters</span>
                                </div>
                            </div>

                            <!-- Password confirmation -->
                            <div class="form-group">
                                <label for="passwordconfirm" class="col-sm-3 control-label">Password (confirm)</label>
                                <div class="col-sm-8">
                                    <input type="password" class="form-control" id="passwordconfirm" name="passwordconfirm" required aria-required="true" pattern=".{5,}" title="5 or more characters">
                                </div>
                            </div>

                            <div id='registration-alert' class='alert-warning'></div>
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

        <!-- Bootstrap Core JavaScript -->
        <script type="text/javascript" src="js/bootstrap.min.js"></script>

        <script type="text/javascript">
                        // Validate password match
                        function validateRegistrationForm() {
                            var pwd = document.forms["addhost_form"]["password"].value;
                            var pwdConfirm = document.forms["addhost_form"]["passwordconfirm"].value;

                            if (pwd !== pwdConfirm) {
                                $("#registration-alert").replaceWith("<div id='registration-alert' class='alert-warning'>Passwords do not match</div>");
                                return false;
                            } else {
                                document.getElementById("addhost_form").submit();
                            }
                        }
        </script>
    </body>
</html>
