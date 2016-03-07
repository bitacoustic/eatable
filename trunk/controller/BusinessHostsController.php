<?php

include_once('model/Owner.php');
include_once('model/Host.php');
include_once('model/Restaurant.php');

class BusinessHostsController {

    private $user;
    private $restaurant;
    private $hosts;

    public function __construct() {
        // Check that cookie exists.
        if (isset($_COOKIE['eatable-user-session'])) {
            $this->user = Owner::getRestaurantOwnerBySessionId($_COOKIE['eatable-user-session']);
        } else {
            $this->user = new Owner();
        }

        if ($this->user->isOwner()) {
            $this->restaurant = Restaurant::getRestaurantByEmployeeId($this->user->id);
            $this->hosts = Host::getHostsByRestaurantId($this->restaurant->id);
        }
    }

    public function invoke() {
        if (!($this->user->verify())) {
            include 'view/page/landing_page.php';
        } else {
            $currentPage = "hosts";
            include 'view/page/business_hosts.php';
        }
    }

    /**
     * Register a new host account and associate it with this restaurant.
     */
    public function addHost() {
        if (!($this->user->verify())) {
            include 'view/page/landing_page.php';
        } else {
            // Register new host
            if (isset($_POST["fname"]) && isset($_POST["lname"]) &&
                    isset($_POST["email"]) && isset($_POST["password"])) {
                $newHost = new Host(NIL, htmlspecialchars($_POST["fname"]), htmlspecialchars($_POST["lname"]), htmlspecialchars($_POST["email"]), htmlspecialchars($_POST["password"]), NIL, NIL, NIL);
            }
            $newHost->registerUser();
            $newHost->populateUserFieldsByEmail();
            Employee::setEmployee($newHost->id, $this->restaurant->id);
            Host::setHost($newHost->id);

            // Refresh list of hosts
            $this->hosts = Host::getHostsByRestaurantId($this->restaurant->id);

            $currentPage = "hosts";
            include 'view/page/business_hosts.php';
        }
    }

    /**
     * Deletes a host account.
     */
    public function removeHost() {
        if (!($this->user->verify())) {
            include 'view/page/landing_page.php';
        } else {
            // Revoke host and employee permissions on given user then
            // delete user's account.
            $removeId = intval($_POST['id']);
            Host::unsetHost($removeId);
            Employee::unsetEmployee($removeId, $this->restaurant->id);
            User::removeById($removeId);

            // Refresh list of hosts
            $this->hosts = Host::getHostsByRestaurantId($this->restaurant->id);

            $currentPage = "hosts";
            include 'view/page/business_hosts.php';
        }
    }

}
