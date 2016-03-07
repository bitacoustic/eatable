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

        <!-- Custom CSS -->
        <link href="css/modern-business.css" rel="stylesheet">
        <link href="css/eatable.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <!--        <link href="font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">-->

        <!-- Datetimepicker -->
        <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css">

    </head>

    <body>
        <!-- Navigation bar -->
        <?php include_once('view/module/navigation.php') ?>

        <!-- Page Content -->
        <div class="container">

            <!-- Search -->
            <?php include_once('view/module/search.php') ?>

            <!-- Page Heading/Breadcrumbs -->

            <?php
            // Show number of results and echo search parameters
            if ($this->restaurantResults) {
                foreach ($this->restaurantResults as $index => $result) {
                    if (!($result->approved)) {
                        unset($this->restaurantResults[$index]);
                    }
                }
                echo '<h2 class="page-header">';
                if (count(array_filter($this->restaurantResults)) == 1) {
                    echo "1 result";
                } else {
                    echo count(array_filter($this->restaurantResults)) . " results";
                }
                if (strcmp(htmlspecialchars($_GET['textsearch']), "") !== 0) {
                    echo " for <span class='font-orange'>" . htmlspecialchars($_GET['textsearch']) . "</span>";
                }
                echo "<br/><small style='font-size: 0.8em'>Showing available reservations for <span class='font-orange'>"
                . htmlspecialchars($_GET['partysizesearch']) . "</span> on <span class='font-orange'>"
                . htmlspecialchars($_GET['selecteddatesearch']) . "</span> around <span class='font-orange'>"
                . htmlspecialchars($_GET['selectedtimesearch']) . "</span></small></h2>";
            } else {
                echo "<h2 class='page-header'>No results for <span class='font-orange'>"
                . htmlspecialchars($_GET['textsearch']) . '</span></h2>' . "\n";
            }
            ?>

            <?php
            if ($this->restaurantResults != NIL) {
                echo "<div class='row'>";
                foreach ($this->restaurantResults as $restaurant) {
                    ?>
                    <div class="col-md-6" style="min-height: 300px;">
                        <form action="index.php" method="GET" id="reservation" class="boxedform" style="padding: 10px; background: #f1ebe7;">
                            <div class="row" style="min-height: 200px;">
                                <div class="col-lg-7">
                                    <h3 style="margin-top: 0; padding-top: 0;"><a href="javascript: submitform(<?php echo $restaurant->id ?>)"><?php echo $restaurant->name ?></a></h3>
                                    <h4><?php echo $restaurant->cuisineType ?></h4>
                                    <p><i class="glyphicon glyphicon-home"></i>&nbsp;&nbsp;<?php echo $restaurant->addressStreet . ", " . $restaurant->addressCity . ", " . $restaurant->addressState . " " . $restaurant->addressPostalCode ?></p>
                                    <p><i class="glyphicon glyphicon-earphone"></i>&nbsp;&nbsp;<?php echo $restaurant->phoneNumber ?></p>
                                    <p><i class="glyphicon glyphicon-search"></i>&nbsp;&nbsp;<?php echo $restaurant->keywords ?></p>
                                </div>
                                <div class="col-lg-5">
                                    <a  href="javascript: submitform(<?php echo $restaurant->id ?>)">
                                        <img class="img-responsive img-hover" style="width:200px; height:125px; margin-top: 25px; margin-bottom: 25px; position: relative; right: 0;" src="<?php echo $restaurant->getImage(); ?>" alt="">	
                                    </a>
                                </div>
                            </div>

                            <div class="row" id="selectedTimeButtonID" style="text-align: center;">
                                <p>
                                    <?php
                                    /*
                                     * Show reservation availability around selected date/time. 
                                     */

                                    // Get timestamps corresponding to 1 hour and 30 minutes before,
                                    // at, and 30 minutes and 1 hour after the selected date/time.
                                    $selectedTimestamp = strtotime(str_replace("-", "/", htmlspecialchars($_GET['selecteddatesearch']))
                                            . " " . htmlspecialchars($_GET['selectedtimesearch']));
                                    $timestampsToDisplay = array(
                                        strtotime("-1 hour", $selectedTimestamp),
                                        strtotime("-30 minutes", $selectedTimestamp),
                                        $selectedTimestamp,
                                        strtotime("+30 minutes", $selectedTimestamp),
                                        strtotime("+1 hour", $selectedTimestamp)
                                    );
                                    $partySizeSearch = intval(htmlspecialchars($_GET['partysizesearch']));

                                    // Display the reservation availability for each timestamp.
                                    foreach ($timestampsToDisplay as $index => $currentTimestamp) {
                                        $isAvailable = Reservation::queryReservation($restaurant->id, date("Y-m-d", $currentTimestamp), date("H:i:s", $currentTimestamp), $partySizeSearch);
                                        if (is_object($isAvailable)) {
                                            if ($index === 2) { // Display border to indicate this is the selected date/time.
                                                echo '<a class="btn btn-primary" name="reservedtime" data-num="' . $restaurant->id . '" href="#" style="border: 3px solid #ae8266;">' . date("g:i A", $currentTimestamp) . "</a>\n";
                                            } else {
                                                echo '<a class="btn btn-primary" name="reservedtime" data-num="' . $restaurant->id . '" href="#">' . date("g:i A", $currentTimestamp) . "</a>\n";
                                            }
                                        } else {
                                            if ($index === 2) { // Display border to indicate this is the selected date/time.
                                                echo '<a class="btn btn-default disabled" name="reservedtime" data-num="' . $restaurant->id . '" href="#" style="border: 3px solid #ae8266;">' . date("g:i A", $currentTimestamp) . "</a>\n";
                                            } else {
                                                echo '<a class="btn btn-default disabled" name="reservedtime" data-num="' . $restaurant->id . '" href="#">' . date("g:i A", $currentTimestamp) . "</a>\n";
                                            }
                                        }
                                    }
                                    ?>
                                </p>
                            </div>

                            <input type="hidden" value="" id="selectedTimeID" name="selectedtimesearch"/>
                            <input type="hidden" value="" id="partysizeid" name="partysizesearch"/>
                            <input type="hidden" value="<?php echo $_GET['selecteddatesearch']; ?>" id="selectedDateID" name="selecteddatesearch"/>
                            <input type="hidden" name="restaurant-id" id="restaurant-ID" value="<?php echo $restaurant->id; ?>">
                            <input type="hidden" name="controller" id="controllerID" value="ReservationForm">
                            <input type="hidden" name="action" value="invoke">
                        </form>
                    </div>
                    <?php
                }
                echo "</div>";
            }
//        else {
//            echo "No results for " . $_GET['textsearch'];
//        }
            ?>

            <!-- Pagination -->
            <!--            <div class="row text-center">
                            <div class="col-lg-12">
                                <ul class="pagination">
                                    <li>
                                        <a href="#">&laquo;</a>
                                    </li>
                                    <li class="active">
                                        <a href="#">1</a>
                                    </li>
                                    <li>
                                        <a href="#">2</a>
                                    </li>
                                    <li>
                                        <a href="#">3</a>
                                    </li>
                                    <li>
                                        <a href="#">4</a>
                                    </li>
                                    <li>
                                        <a href="#">5</a>
                                    </li>
                                    <li>
                                        <a href="#">&raquo;</a>
                                    </li>
                                </ul>
                            </div>
                        </div>-->
            <!-- /.row -->
        </div>

        <!-- Footer -->
        <?php include_once('view/module/footer.php'); ?>

        <!-- jQuery Version 1.11.0 -->
        <script src="js/jquery-1.11.0.js"></script>

        <!-- depencencies for Datetimepicker -->
        <script type="text/javascript" src="js/moment.min.js"></script>
        <script type="text/javascript" src="js/transition.js"></script>
        <script type="text/javascript" src="js/collapse.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script type="text/javascript" src="js/bootstrap.min.js"></script>

        <!-- Bootstrap Datetimepicker -->
        <script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>

        <script type="text/javascript" src="js/search.js"></script>

        <script type="text/javascript">
            function submitform(id) {
                $('#controllerID').val("Restaurant");
                $('#selectedTimeID').val($("#selectedtimesearch").val());
                //$('#selectedDateID').val($('#selecteddatesearch').val());
                $('#partysizeid').val($("#partysizesearch").val());
                $('#restaurant-ID').val(id);
                document.getElementById("reservation").submit();
            }

            $(document).ready(function () {
                $('[name=reservedtime]').click(function () {
                    var selectTime = $(this).text();
                    $('#selectedTimeID').val(selectTime);
                    $('#controllerID').val("ReservationForm");
                    //$('#selectedDateID').val($('#selecteddatesearch').val());
                    $('#partysizeid').val($("#partysizesearch").val());
                    $('#restaurant-ID').val($(this).attr("data-num"));
                    document.getElementById("reservation").submit();
                });
            });
        </script>
    </body>

</html>
