<h2>Search for Reservations</h2>

<!-- THIS SEARCH FORM PASSES FOUR GET VARIABLES TO THE ROUTER FILE (index.php)
    1) a contoller argument (Search)
    2) an action argument (invoke)  
    3) a search term (textsearch) 
    4) a search type (typesearch) -->

<div class="search">
    <div class="container container-search">
        <div class="form-section">
            <div class="row" style="margin: 0;">
                <form action="index.php" method="GET" role="search" id="searchForm">
                    <input type="hidden" name="controller" value="Search">
                    <input type="hidden" name="action" value="invoke">
                    <input type="hidden" name="citybrowse" value="0">

                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="sr-only" for="partysizesearch">Guest</label>
                            <select class="form-control" id="partysizesearch" name="partysizesearch">
                                <option default="default"><?php
                                    if (isset($_GET['partysizesearch'])) {
                                        echo $_GET['partysizesearch'];
                                    } else {
                                        echo "4 people";
                                    }
                                    ?>
                                <option disabled="disabled">-------</option>
                                <option value="1 person">1 person</option>
                                <option value="2 people">2 people</option>
                                <option value="3 people">3 people</option>
                                <option value="4 people">4 people</option>
                                <option value="5 people">5 people</option>
                                <option value="6 people">6 people</option>
                                <option value="7 people">7 people</option>
                                <option value="8 people">8 people</option>
                                <option value="9 people">9 people</option>
                                <option value="10 people">10 people</option>
                                <option value="11 people">11 people</option>
                                <option value="12 people">12 people</option>  
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <!-- start datetimepicker -->
                        <div class="form-group">
                            <label class="sr-only" for="selecteddatesearch">Reservation Date</label>
                            <div class='input-group date' id='datetimepicker_search_date'>
                                <input type='text' class="form-control" id="selecteddatesearch" name="selecteddatesearch" placeholder="MM-DD-YYYY" value="<?php
                                if (isset($_GET['selecteddatesearch'])) {
                                    echo $_GET['selecteddatesearch'];
                                } else {
                                    // If it is after 7:00pm, display tomorrow's date, otherwise
                                    // display today's date.
                                    if (time() > (strtotime(date("Y-m-d") . " 19:00:00"))) {
                                        echo date("m-d-Y", strtotime("+1 day"));
                                    } else {
                                        echo date("m-d-Y");
                                    }
                                }
                                ?>" title="Reservation date" />
                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                            </div>
                        </div>
                        <!-- end datetimepicker -->
                    </div>
                    
                    <div class="col-md-2">
                        <!-- start datetimepicker -->
                        <div class="form-group">
                            <div class="input-group date" id="datetimepicker_search_time">
                                <label class="sr-only" for="selectedtimesearch">Reservation Time</label>
                                <input type='text' class="form-control" id="selectedtimesearch" name="selectedtimesearch" placeholder="7:00 PM" value="<?php
                                if (isset($_GET['selectedtimesearch'])) {
                                    echo $_GET['selectedtimesearch'];
                                } else {
                                    echo "7:00 PM";
                                }
                                ?>" title="Reservation time"/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                </span>
                            </div>
                        </div>  
                        <!-- end datetimepicker -->
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="sr-only" for="textsearch">Search Terms</label>
                            <input type="text" size="60" class="form-control" placeholder="Restaurant name, location, and/or cuisine" name="textsearch" value="<?php
                            if (isset($_GET['textsearch'])) {
                                echo $_GET['textsearch'];
                            } else {
                                echo "";
                            }
                            ?>" title="Search terms (restaurant name, location and/or cuisine type)">
                        </div>
                    </div>

                    <div class="col-md-2" style="padding-bottom:15px;">
                        <a class="btn btn-primary"  href="javascript:submitSearch()">Find Restaurants</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
