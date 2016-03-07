<?php

include_once('model/User.php');
include_once('model/Administrator.php');
include_once('model/Employee.php');
include_once('model/Owner.php');
include_once('model/Host.php');

include_once('model/Restaurant.php');

class AdminStatisticsController {

    private $user;

    public function __construct() {
        // Check that cookie exists.
        if (isset($_COOKIE['eatable-user-session'])) {
            $this->user = Administrator::getAdminBySessionId($_COOKIE['eatable-user-session']);
        } else {
            $this->user = new Administrator();
        }
    }

    public function invoke() {
        if (!($this->user->verify())) {
            include 'view/page/landing_page.php';
        } else {
            $statistics = True;
            include 'view/page/admin_statistics.php';
        }
    }

}
