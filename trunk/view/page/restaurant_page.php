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

        <!-- Datetimepicker -->
        <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css">


        <script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
        <script>
            function initialize() {

                var latlng = new google.maps.LatLng(-34.397, 150.644);
                var mapCanvas = document.getElementById('map');
                var mapOptions = {
                    center: latlng,
                    zoom: 14,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                }

                var map = new google.maps.Map(mapCanvas, mapOptions);

                var geocoder = new google.maps.Geocoder();
                var address = '<?php echo $this->restaurant->addressStreet . ", " . $this->restaurant->addressCity ?>';

                geocoder.geocode({'address': address}, function (results, status) {
                    if (status === google.maps.GeocoderStatus.OK) {
                        map.setCenter(results[0].geometry.location);
                        var marker = new google.maps.Marker({
                            map: map,
                            position: results[0].geometry.location
                        });
                        marker.setMap(map);
                    } else {
                        alert("Geocode was not successful for the following reason: " + status);
                    }
                });

            }
            google.maps.event.addDomListener(window, 'load', initialize);
        </script>
    </head>

    <body>
        <!-- Navigation bar -->
        <?php include_once('view/module/navigation.php') ?>

        <!-- Page Content -->
        <div class="container">

            <!-- Search -->
            <?php include_once('view/module/search.php') ?>

            <div class="well" id="buttontime" >
                <div class="row form-inline" >
                    <form id="reservation" action="index.php" method="GET">   
                        <h4 style="text-align: center;">Availability for <?php echo $_GET['partysizesearch'] ?>, on <?php echo DateTime::createFromFormat('m-d-Y', $_GET['selecteddatesearch'])->format('l, F jS, Y'); ?> around <?php echo $_GET['selectedtimesearch'] ?></h4>
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
                                    $isAvailable = Reservation::queryReservation($this->restaurant->id, date("Y-m-d", $currentTimestamp), date("H:i:s", $currentTimestamp), $partySizeSearch);
                                    if (is_object($isAvailable)) {
                                        if ($index === 2) { // Display border to indicate this is the selected date/time.
                                            echo '<a class="btn btn-lg btn-primary" name="reservedtime" href="#" style="border: 3px solid #ae8266;">' . date("g:i A", $currentTimestamp) . "</a>\n";
                                        } else {
                                            echo '<a class="btn btn-lg btn-primary" name="reservedtime" href="#">' . date("g:i A", $currentTimestamp) . "</a>\n";
                                        }
                                    } else {
                                        if ($index === 2) { // Display border to indicate this is the selected date/time.
                                            echo '<a class="btn btn-lg btn-default disabled" name="reservedtime" href="#" style="border: 3px solid #ae8266;">' . date("g:i A", $currentTimestamp) . "</a>\n";
                                        } else {
                                            echo '<a class="btn btn-lg btn-default disabled" name="reservedtime" href="#">' . date("g:i A", $currentTimestamp) . "</a>\n";
                                        }
                                    }
                                }
                                ?>
                            </p>
                        </div>
                                <!--<p id="selectedTimeButtonID">
                                    <a class="btn btn-lg btn-primary " style="margin-left: 20px" href="#">7:00 PM</a>
                                    <a class="btn btn-lg btn-primary " style="margin-left: 20px" href="#">7:30 PM</a>
                                    <a class="btn btn-lg btn-primary " style="margin-left: 20px" href="#">8:00 PM</a>
                                    <a class="btn btn-lg btn-primary " style="margin-left: 20px" href="#">8:30 PM</a>
                                    <a class="btn btn-lg btn-primary " style="margin-left: 20px" href="#">9:30 PM</a>
                                </p>-->
                        <input type="hidden" value="7:00 PM" id="selectedTimeID" name="selectedtimesearch"/>
                        <input type="hidden" value="4 people" id="partysizeid" name="partysizesearch"/>
                        <input type="hidden" value="<?php echo date("m-d-Y") ?>" id="selectedDateID" name="selecteddatesearch"/>
                        <input type="hidden" value="<?php echo $this->restaurant->id ?>" id="restaurant-id" name="restaurant-id"/>
                        <input type="hidden" name="controller" value="ReservationForm">
                        <input type="hidden" name="action" value="invoke">
                    </form>
                </div>
            </div>


            <div class="row">
                <div class="col-md-6">
                    <img class="img-responsive" style="width:500px;height:350px; margin-top: 70px" src="<?php echo $this->restaurant->getImage() ?>" alt="">
                </div>
                <div class="col-md-6">
                    <h2 style="color: #8a6d3b"><u><?php echo $this->restaurant->name ?></u></h2>
                    <p><b>Address : </b><?php echo $this->restaurant->addressStreet . ", " . $this->restaurant->addressCity . ", " . $this->restaurant->addressState . ", " . $this->restaurant->addressPostalCode ?></p>
                    <p><b>Phone : </b><?php echo $this->restaurant->phoneNumber ?></p>
                    <p><b>Cuisine : </b><?php echo $this->restaurant->keywords ?></p>
                    <p><b>Hours : </b><br/>
                        <?php
                        if ($this->hours) {
                            foreach ($this->hours as $range) {

                                echo RestaurantHours::getDayOfWeekString($range->dayOfWeek)
                                . " " . date("g:i A", strtotime("2015-01-01 " . $range->startTime))
                                . " - " . date("g:i A", strtotime("2015-01-01 " . $range->endTime)) . "<br/>";
                            }
                        } else {
                            echo "Unavailable -- call restaurant";
                        }
                        ?>

                    </p>
                </div>
            </div>
            <!-- /.row -->


            <div class="row" style="margin-top: 30px">
                <div class="col-md-6">
                    <div id="map" style="width:500px;height:350px;" ></div>
                </div>

            </div>
            <!-- /.row -->
            <hr>
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
            $('#findTable').click(function () {
                //window.location.reload();
                $('#buttontime').show();
            });

            $(document).ready(function () {
                $('[name=reservedtime]').click(function () {
                    var selectTime = $(this).text();
                    $('#selectedTimeID').val(selectTime);
                    $('#controllerID').val("ReservationForm");
                    $('#selectedDateID').val($('#selecteddatesearch').val());
                    $('#partysizeid').val($("#partysizesearch").val());
                    $('#restaurant-ID').val($(this).attr("data-num"));
                    document.getElementById("reservation").submit();
                });
            });
        </script>
    </body>
</html>
