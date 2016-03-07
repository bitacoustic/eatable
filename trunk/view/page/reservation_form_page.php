<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Eatable</title>

        <!-- Bootstrap Core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <link href="css/datepicker.css" rel="stylesheet"> 
        <link href="css/datepicker.less" rel="stylesheet" type="text/css" />

        <!-- Custom CSS -->
        <link href="css/modern-business.css" rel="stylesheet">
        <link href="css/eatable.css" rel="stylesheet">
    </head>

    <body>
        <!-- Navigation -->
        <?php include_once('view/module/navigation.php') ?>
		
        <!-- Page Content -->
        <div class="container">
            <!-- Page Heading/Breadcrumbs -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Reservation @ <?php echo $this->restaurant->name; ?></h1>
                </div>
            </div>

            <!-- Login Modal -->
            <div id="loginModal" class="well" style="display: <?php echo $this->loginContainer ?>;">
                <form class="form-horizontal" id='login_form' method='post' action='index.php'>
                    
                    <h4 style="margin-top: -5px" class="page-header" id="myModalLabel">General User Login</h4>
                    <div class="modal-body">
                        <!-- Email -->
                        <div class="form-group">
                            <label for="email" class="col-sm-3 control-label">Email address: </label>
                            <div class="col-sm-8">
                                <input type='text' class='form-control' name='email' placeholder='Email'>
                            </div>
                        </div>
                        <!-- Password -->
                        <div class="form-group">
                            <label for="newpassword" class="col-sm-3 control-label">Password: </label>
                            <div class="col-sm-8">
                                <input type='password' class='form-control' name='password' placeholder='Password'>
                            </div>
                        </div>
                    </div>
                    <div style="text-align: center; margin-top: 5px" >
                        <button type='submit' class='btn btn-primary'>Login</button>
                        <!-- <button onclick="loginform()" class='btn btn-primary'>Login</button> -->
                        <a data-toggle="modal" data-target="#registrationModal" class='btn btn-primary'>Register</a> 
                    </div>
                </form>
            </div>
            
            <!-- Contact Form -->
            <!-- In order to set the email address and subject line for the contact form go to the bin/contact_me.php file. -->
            <div class="well" style="display: <?php echo $this->loginInfo ?>;">
                <div class="row">
                    <div class="col-md-12">
                        <h2 style="margin-top: -5px" class="page-header">Confirm User Information</h2>
                        <form action="reservationConfirm.php" method="POST" data-toggle="validator">
                            <div class="control-group form-group form-inline" style="padding-bottom: 40px;">
                                <div class="controls">
                                    <div class="col-xs-5">
                                        <label class="col-sm-4 control-label">First Name:</label>
                                        <div class="col-sm-8">
                                            <span id="firstnameForm"><?php echo $this->user->fname ?> </span>
                                        </div>
                                    </div>

                                    <div class="col-xs-5">
                                        <label class="col-sm-4 control-label">Last Name:</label>
                                        <div class="col-sm-8">
                                            <span  id="lastnameForm"><?php echo $this->user->lname ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="control-group form-group form-inline" style="padding-bottom: 40px;">
                                <div class="controls">
                                    <div class="col-xs-5">
                                        <label class="col-sm-4 control-label">Email:</label>
                                        <div class="col-sm-8">
                                            <span id="emailForm"><?php echo $this->user->email ?></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-5">
                                        <label class="col-sm-4 control-label">Phone Number:</label>
                                        <div class="col-sm-8">
                                            <span id="phoneForm" ><?php echo $this->user->phone_number ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12">
                                &nbsp;&nbsp;&nbsp;<strong>Special Instructions (optional)</strong><br/>
                                <div class="col-sm-1"></div>
                                <div class="col-sm-9">
                                    <textarea class='form-control' rows='3' id="instructionForm" name='message'></textarea>
                                </div>
                            </div>

                            <div id="success"></div>
                        </form>
                    </div>
                </div>
            </div>
            <hr>

            <div class="well" >
                <div class="row">
                    <!-- Contact Details Column -->
                    <div class="col-md-12">
                        <h2 style="margin-top: -5px" class="page-header">My Reservation</h2>
                        <div class="col-md-3">
                            <b>Restaurant: </b><span class='font-greenblackhighlight'><?php echo $this->restaurant->name ?></span>
                        </div>
                        <div class="col-md-3">
                            <b>Party Size: </b><span class='font-greenblackhighlight'><?php echo $this->reservation->partySize; ?></span>
                        </div>
                        <div class="col-md-3">
                            <b>Date: </b><span class='font-greenblackhighlight'><?php echo $this->reservation->date; ?></span>
                        </div>
                        <div class="col-md-3">
                            <b>Time: </b><span class='font-greenblackhighlight'><?php echo $this->reservation->time ?> </span>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.well -->
            <div style="text-align:center; display: <?php echo $this->loginInfo ;?>" >
                <button   class="btn btn-primary" id="myBtn">Complete Reservation</button>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Would you like to place an order with your reservation?</h4>
                    </div>
                    <form action="index.php" method="GET" id="reservationForm">
                        <div style="text-align: center; margin-top: 20px; margin-bottom: 20px">
                            <button type="submit" class="btn btn-primary" id="order_submitID" >Yes, please.</button>
                            <button type="submit" class="btn btn-primary" id="confirm_reservation_submitID">No, I'm done.</button>
                        </div>
                        <input type="hidden" value="<?php echo $this->restaurant->id ?>" id="restaurantID" name="restaurant-id"/>
                        <input type="hidden" value="<?php echo $this->reservation->date; ?>" id="date" name="selectedDate"/>
                        <input type="hidden" value="<?php echo $this->reservation->time ?> " id="time" name="selectedTime"/>
                        <input type="hidden" value="<?php echo $this->reservation->partySize; ?>" id="partysize" name="partySize"/>

                        <input type="hidden" id="firstnameID" name="firstname"/>
                        <input type="hidden" id="lastnameID" name="lastname"/>
                        <input type="hidden" id="emailID" name="email"/>
                        <input type="hidden" id="phonenumberID" name="phonenumber"/>
                        <input type="hidden" id="specialinstructionID" name="specialinstruction"/>
                        <input type="hidden" name="controller" value="ReservationConfirm" id="reservationConfirmID">
                        <input type="hidden" name="action" value="invoke">
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
                            <input type="text" class="form-control" id="fname" name="fname" placeholder="Joseph" aria-describedby="fname-format" required aria-required="true" pattern="[A-Za-z-']+" title="Valid characters are A-Z a-z - '">
                            <span id="fname-format" class="help">Format: Valid characters are A-Z a-z - '</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="lname" class="col-sm-3 control-label">Last name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="lname" name="lname" placeholder="Test" aria-describedby="fname-format" required aria-required="true" pattern="[A-Za-z-']+" title="Valid characters are A-Z a-z - '">
                            <span id="lname-format" class="help">Format: Valid characters are A-Z a-z - '</span>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" class="col-sm-3 control-label">Email address</label>
                        <div class="col-sm-8">
                            <input type="email" class="form-control" id="email" name="email" placeholder="test@example.com" required aria-required="true">
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

        <!-- Footer -->
        <?php include_once('view/module/footer.php'); ?>

        <!-- jQuery Version 1.11.0 -->
        <script type="text/javascript"  src="js/jquery-1.11.0.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script type="text/javascript"  src="js/bootstrap.min.js"></script>

        <!-- Bootstrap Validation -->
        <script type="text/javascript"  src="js/jqBootstrapValidation.js"></script>
        <!--<script src="js/reservation_form_validation.js"></script>-->

        <script type="text/javascript">
            $(document).ready(function () {
                $("#myBtn").click(function () {
                    /*if ($('#firstnameForm').val() === '' || $('#lastnameForm').val() === '' || $('#emailForm').val() === '' || $('#phoneForm').val() === '') {
                        alert('Please include all required fields');
                        return false;
                    }*/
                    $("#myModal").modal();
                });
            });

            $(document).ready(function () {
                $("#confirm_reservation_submitID").click(function () {
                    $('#firstnameID').val($('#firstnameForm').text());
                    $('#lastnameID').val($('#lastnameForm').text());
                    $('#emailID').val($('#emailForm').text());
                    $('#phonenumberID').val($('#phoneForm').text());
                    $('#specialinstructionID').val($('#instructionForm').val());
                    document.getElementById("reservationForm").submit();

                });
            });

            $(document).ready(function () {
                $("#order_submitID").click(function () {
                    $('#firstnameID').val($('#firstnameForm').text());
                    $('#lastnameID').val($('#lastnameForm').text());
                    $('#emailID').val($('#emailForm').text());
                    $('#phonenumberID').val($('#phoneForm').text());
                    $('#specialinstructionID').val($('#instructionForm').val());
                    $('#reservationConfirmID').val('Order');
                    document.getElementById("reservationForm").submit();

                });
            });
            $(document).ready(function () {
                $('#phoneForm').keyup(function () {
                    this.value = this.value.replace(/^(\d{3})(\d)/, '$1-$2').replace(/^(\d{3}-\d{3})(\d)/, '$1-$2');
                });
            });
            
            function loginform(){
                document.getElementById("login_form").submit();
            }
            
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
    </body>
</html>
