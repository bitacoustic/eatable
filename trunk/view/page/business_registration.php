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
            <div class="row">
                <div class="col-md-12">
                    <h2>Are you a restaurant owner? List your restaurant with us.</h2>
                    <p>Drive more business with online table reservation and order-ahead functionality.</p>
                    <p>Ready to get started? Register now.</p>

                    <!-- Registration form -->
                    <form class="form-horizontal boxedform" role="register" id="owner_registration_form" name="registration_form" method="post" action="index.php?controller=BusinessRegistration&action=registerNewOwner" onsubmit="return validateOwnerRegistrationForm()">
                        <h3 style="text-align: center" class='boxedform-title'>Business Owner Registration</h3>
                        <br/>

                        <div class="form-group">
                            <label for="fname" class="col-xs-3 control-label">First name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="owner_fname" name="fname" placeholder="" aria-describedby="fname-format" required aria-required="true" pattern="[A-Za-z-']+" title="Valid characters are A-Z a-z - '">
                                <span id="fname-format" class="help">Format: Valid characters are A-Z a-z - '</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="lname" class="col-sm-3 control-label">Last name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="owner_lname" name="lname" placeholder="" aria-describedby="fname-format" required aria-required="true" pattern="[A-Za-z-']+" title="Valid characters are A-Z a-z - '">
                                <span id="lname-format" class="help">Format: Valid characters are A-Z a-z - '</span>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email" class="col-sm-3 control-label">Email address</label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control" id="owner_email" name="email" placeholder="user@example.com" required aria-required="true">
                            </div>
                        </div>

                        <!-- Phone number -->
                        <div class="form-group">
                            <label for="phone" class="col-sm-3 control-label">Phone number</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="owner_phone" name="phone" aria-describedby="phone-format" required aria-required="true" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" title="Area code and number, e.g. 415-555-1234">
                                <span id="phone-format" class="help">The primary contact phone number for your business. Use format: Area code and number, e.g. 415-555-1234</span>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="form-group">
                            <label for="password" class="col-sm-3 control-label">Password</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" id="owner_password" name="password" required aria-required="true" pattern=".{5,}" title="5 or more characters">
                                <span id="pass-format" class="help">Choose a password of 5 or more characters</span>
                            </div>
                        </div>

                        <!-- Password confirmation -->
                        <div class="form-group">
                            <label for="passwordconfirm" class="col-sm-3 control-label">Password (confirm)</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" id="owner_passwordconfirm" name="passwordconfirm" required aria-required="true" pattern=".{5,}" title="5 or more characters">
                            </div>
                        </div>

                        <!-- Terms of Service -->
                        <div class="form-group">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-8">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" id="owner_tos" value="" name="tos">
                                        I agree 
                                    </label>
                                    to the <a href="index.php?controller=TOS&action=invoke" target='_blank'>Terms of Service</a> (opens in a new tab).
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="form-name" value="registration-form">

                        <div class='well'>Once registered, you will be taken to the restaurant management page, where you can input your restaurant details. In the future, select <code>Manage my restaurant</code> in the navigation bar to access this area.</div>

                        <div id="owner-registration-alert" class="alert-warning"> </div>

                        <div style="text-align: center">
                            <button type="submit" class="btn btn-primary">Register</button>
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

        <script>
                        // Janky form validation; most is handled by awesome HTML5 forms
                        function validateOwnerRegistrationForm() {
                            var pwd = document.forms["owner_registration_form"]["password"].value;
                            var pwdConfirm = document.forms["owner_registration_form"]["passwordconfirm"].value;
                            var tosChecked = $("#owner_tos").is(":checked");

                            if (pwd !== pwdConfirm) {
                                $("#owner-registration-alert").replaceWith("<div id='owner-registration-alert' class='alert-warning'>Passwords do not match</div>");
                                return false;
                            }
                            
                            if (!tosChecked) {
                                $("#owner-registration-alert").replaceWith("<div id='owner-registration-alert' class='alert-warning'>You must agree to the Terms of Service.</div>");
                                return false;
                            }
                        }
        </script>
    </body>
</html>
