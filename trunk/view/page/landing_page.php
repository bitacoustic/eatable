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
        <!--        <link href="font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">-->

        <!-- Datetimepicker -->
        <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css">

        <link href="css/datepicker.css" rel="stylesheet"> 
        <link href="css/datepicker.less" rel="stylesheet" type="text/css" />

    </head>

    <body>
        <!-- Navigation bar -->
        <?php include_once('view/module/navigation.php') ?>

        <!-- Page Content -->
        <div class="container">

            <!-- Search -->
            <?php include_once('view/module/search.php'); ?>
            
            <div class="well" style="background: #57594b; color: #f6f6f6; margin-bottom: 0; padding: 5px;">
                <h2 class="text-center" style="text-shadow: none; margin-top: 10px;">1. Reserve a table.&nbsp; 2. Place your meal order in advance <small style="color: #c7c7c7;">(optional)</small>.&nbsp; 3. Head out and enjoy your meal.</h2>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <!-- Browse Location Section -->
                    <form action="index.php" method="GET" id="browseLocation" class="navbar-form navbar-left" role="search">
                        <div class="row">
                            <input type="hidden" name="controller" value="Search">
                            <input type="hidden" name="action" value="invoke">
                            <input type="hidden" name="textsearch" value="" id="cityBrowseID">
                            <input type="hidden" name="citybrowse" value="1">
                            <div class="col-lg-12">
                                <h2>Browse Restaurants by City</h2>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <a  onclick="citySubmit('San Francisco')" >
                                    <img class="img-responsive img-portfolio img-hover img-landing" src="db/img/sanfrancisco.jpg" alt="">
                                    <div class="carousel-caption" style="margin-bottom: 50px" >
                                        <h2 class="img-landing-caption">San Francisco</h2>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <a  onclick="citySubmit('Burlingame')" >
                                    <img class="img-responsive img-portfolio img-hover img-landing" src="db/img/burlingame.jpg"  alt="">
                                    <div class="carousel-caption" style="margin-bottom: 50px" >
                                        <h2 class="img-landing-caption">Burlingame</h2>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <a  onclick="citySubmit('Los Angeles')">
                                    <img class="img-responsive img-portfolio img-hover img-landing" src="db/img/losangeles.jpg"  alt="">
                                    <div class="carousel-caption" style="margin-bottom: 50px" >
                                        <h2 class="img-landing-caption">Los Angeles</h2>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <input type="hidden" value="4 people"  name="partysizesearch"/>
                        <input type="hidden" value="7:00 PM" name="selectedtimesearch"/>
                        <input type="hidden" value="<?php echo date("m-d-Y") ?>"  name="selecteddatesearch"/>
                        <!-- /.row -->
                    </form>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-12">
                    <!-- Browse Cuisine Section -->
                    <form action="index.php" method="GET" id="browseCuisine" class="navbar-form navbar-left" role="search">
                        <div class="row">
                            <input type="hidden" name="controller" value="Search">
                            <input type="hidden" name="action" value="invoke">
                            <input type="hidden" name="textsearch" value="" id="textSearchBrowseID">
                            <input type="hidden" name="citybrowse" value="0" >
                            <div class="col-lg-12">
                                <h2>Browse Restaurants by Cuisine</h2>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <a  onclick="browseSubmit('burgers')" >
                                    <img class="img-responsive img-portfolio img-hover img-landing" src="db/img/burger.jpg" alt="">
                                    <div class="carousel-caption" style="margin-bottom: 50px" >
                                        <h2 class="img-landing-caption">Burger</h2>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <a  onclick="browseSubmit('sushi')" >
                                    <img class="img-responsive img-portfolio img-hover img-landing" src="db/img/sushi.jpg"  alt="">
                                    <div class="carousel-caption" style="margin-bottom: 50px" >
                                        <h2 class="img-landing-caption">Sushi</h2>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <a  onclick="browseSubmit('pizza')">
                                    <img class="img-responsive img-portfolio img-hover img-landing" src="db/img/pizza.jpeg"  alt="">
                                    <div class="carousel-caption" style="margin-bottom: 50px" >
                                        <h2 class="img-landing-caption">Pizza</h2>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <!-- /.row -->
                        <input type="hidden" value="4 people"  name="partysizesearch"/>
                        <input type="hidden" value="7:00 PM"  name="selectedtimesearch"/>
                        <input type="hidden" value="<?php echo date("m-d-Y") ?>"  name="selecteddatesearch"/>
                    </form>
                </div>
            </div>

            <!-- Call to Action Section -->
            <!--            <div class="well">
                            <div class="row">
                                <div class="col-md-6">
                                    <a class="btn btn-lg btn-default btn-block" href="index.php?controller=Business&action=invoke">Restaurant Login</a>
                                </div>
                                <div class="col-md-6">
                                    <a class="btn btn-lg btn-default btn-block" href="index.php?controller=Admin&action=invoke">Admin Login</a>
                                </div>
                            </div>
                        </div>-->

        </div>
        <!-- /.container -->

        <!-- Footer -->
        <?php include_once('view/module/footer.php'); ?>

        <!-- jQuery Version 1.11.0 -->
        <script src="js/jquery-1.11.0.js"></script>

        <!-- depencencies for Datetimepicker -->
        <script type="text/javascript" src="js/moment.min.js"></script>
        <script type="text/javascript" src="js/transition.js"></script>
        <script type="text/javascript" src="js/collapse.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="js/bootstrap.min.js"></script>

        <!-- Bootstrap Datetimepicker -->
        <script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
        <script type="text/javascript" src="js/search.js"></script>

        <script type="text/javascript">
                                    function browseSubmit(type)
                                    {
                                        //document.getElementById('typesearchID').value = '';
                                        document.getElementById('textSearchBrowseID').value = type;
                                        document.getElementById('browseCuisine').submit();
                                    }

                                    function citySubmit(type)
                                    {
                                        //document.getElementById('typesearchID').value = '';
                                        document.getElementById('cityBrowseID').value = type;
                                        document.getElementById('browseLocation').submit();
                                    }
        </script>
    </body>

</html>
