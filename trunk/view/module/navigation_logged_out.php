<!-- Navigation -->
<nav class='navbar navbar-inverse navbar-fixed-top' role='navigation'>
    <div class='container navbar-info' >SFSU&nbsp;Software&nbsp;Engineering&nbsp;Project,&nbsp;Fall&nbsp;2015&nbsp;&mdash;&nbsp;For&nbsp;Demonstration&nbsp;Only</div>
    <div class='navbar-infohighlight'></div>
    <div class='container navbar-info' id='navbar-content'>
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class='navbar-header'>
            <button type='button' class='navbar-toggle' data-toggle='collapse' data-target='#bs-example-navbar-collapse-1'>
                <span class='sr-only'>Toggle navigation</span>
                <span class='icon-bar'></span>
                <span class='icon-bar'></span>
                <span class='icon-bar'></span>
            </button>
            <a class='navbar-brand img-responsive' href='index.php'><img class="img-responsive" src="img/eatable_logo_white_small.png"></a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class='collapse navbar-collapse' id='bs-example-navbar-collapse-1'>
            <form class='navbar-form navbar-right' role='login' id='login_form' method='post' action='index.php'>
                <ul class="nav navbar-nav navbar-right">
                    <li><a data-toggle="modal" data-target="#loginModal">Log in</a></li>
                    <li><a data-toggle="modal" data-target="#registrationModal">Register</a></li>
                    <!-- TODO -->
                    <li><a href="index.php?controller=BusinessRegistration&action=invoke">List your restaurant</a></li>
                </ul>
            </form>
        </div>
    </div>
</nav>

<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="form-horizontal" role='login' id='login_form' method='post' action='index.php'>

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">General User Login</h4>
                </div>

                <div class="modal-body">
                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" class="col-sm-3 control-label">Email address</label>
                        <div class="col-sm-8">
                            <input type='text' class='form-control' name='email' placeholder='Email'>
                        </div>
                    </div>
                    <!-- Password -->
                    <div class="form-group">
                        <label for="newpassword" class="col-sm-3 control-label">Password</label>
                        <div class="col-sm-8">
                            <input type='password' class='form-control' name='password' placeholder='Password'>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type='submit' class='btn btn-primary'>Login</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Registration Modal -->
<div class="modal fade" id="registrationModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="form-horizontal" role="register" id="registration_form" name="registration_form" method="post" action="index.php" onsubmit="return validateRegistrationForm()">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">General User Registration</h4>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label for="fname" class="col-xs-3 control-label">First name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="fname" name="fname" placeholder="" aria-describedby="fname-format" required aria-required="true" pattern="[A-Za-z-']+" title="Valid characters are A-Z a-z - '">
                            <span id="fname-format" class="help">Format: Valid characters are A-Z a-z - '</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="lname" class="col-sm-3 control-label">Last name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="lname" name="lname" placeholder="" aria-describedby="fname-format" required aria-required="true" pattern="[A-Za-z-']+" title="Valid characters are A-Z a-z - '">
                            <span id="lname-format" class="help">Format: Valid characters are A-Z a-z - '</span>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" class="col-sm-3 control-label">Email address</label>
                        <div class="col-sm-8">
                            <input type="email" class="form-control" id="email" name="email" placeholder="" required aria-required="true">
                        </div>
                    </div>

                    <!-- Phone number -->
                    <div class="form-group">
                        <label for="phone" class="col-sm-3 control-label">Phone number</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="phone_number" name="phone_number" aria-describedby="phone-format" required aria-required="true" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" title="Area code and number, e.g. 415-555-1234">
                            <span id="phone-format" class="help">Format: Area code and number, e.g. 415-555-1234</span>
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
                    
                    <!-- Terms of Service -->
                    <div class="form-group">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-8">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="tos" value="" name="tos">
                                    I agree to the Terms of Service below.
                                </label>

                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="form-name" value="registration-form">

                    <!-- Terms of Service text -->
                    <div class='tos-text'>
                        <?php include_once 'view/module/terms_of_service.php' ?>
                    </div>

                    <div id="registration-alert" class="alert-warning"> </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="register-submit" value="Register">Register</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Janky form validation; most is handled by awesome HTML5 forms
    function validateRegistrationForm() {
        var pwd = document.forms["registration_form"]["password"].value;
        var pwdConfirm = document.forms["registration_form"]["passwordconfirm"].value;
        var tosChecked = $("#tos").is(":checked");

        if (pwd !== pwdConfirm) {
            $("#registration-alert").replaceWith("<div id='registration-alert' class='alert-warning'>Passwords do not match</div>");
            return false;
        }

        if (!tosChecked) {
            $("#registration-alert").replaceWith("<div id='registration-alert' class='alert-warning'>You must agree to the Terms of Service.</div>");
            return false;
        }
    }
</script>

