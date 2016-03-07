<?php

include_once('model/User.php');
include_once('model/Reservation.php');

class AccountController {

    private $user;
    private $reservations;

    public function __construct() { //check that cookie exist
        if (isset($_COOKIE['eatable-user-session'])) {
            $this->user = User::getUserBySessionId($_COOKIE['eatable-user-session']);
        } else {
            $this->user = new User();
        }

        $this->reservations = Reservation::getReservationsByUserId($this->user->id);
    }

    public function invoke() {
        // This page is for logged in users only.  Verify user and redirect
        // to the landing page if they are not logged in.
        if (($this->user->verify())) {
            include 'view/page/user_account.php';
        } else {
            include 'view/page/landing_page.php';
        }
    }

    /**
     * Updates the current user's basic account information.
     * Currently first/last name and email account
     */
    public function updateUserAccount() {
        // Update user fields.
        // 
        if (isset($_GET['fname']) && !is_null($_GET['fname'])) {
            $this->user->fname = $_GET['fname'];
        }
        if (isset($_GET['lname']) && !is_null($_GET['lname'])) {
            $this->user->lname = $_GET['lname'];
        }
        if (isset($_GET['email']) && !is_null($_GET['fname'])) {
            $this->user->email = $_GET['email'];
        }
        if (isset($_GET['phone_number']) && !is_null($_GET['phone_number'])) {
            $this->user->phone_number = $_GET['phone_number'];
        }

        // Update the user's record in the database.
        $this->user->updateUser();

        // Reload the page.
        include 'view/page/user_account.php';
    }

    /**
     * Changes the user's password.
     */
    public function updateUserPassword() {
        // Store the current parameters.
        $currentPassword = $_POST["currentPassword"];
        $newPassword = $_POST["newPassword"];
        $newPasswordConfirm = $_POST["newPasswordConfirm"];

        // Check submitted current password against db.
        if ($this->user->confirmPassword($currentPassword)) {
            // Ensure current password is not empty.
            // Update the object password.
            $this->user->password = $_POST['newPasswordConfirm'];
            // Update the user password in tha database.
            $this->user->updateUserPassword();
            // Reload the page.
            include 'view/page/user_account.php';
        } else {
            echo "error";
        }
    }

    /**
     * Cancel a reservation. Marks the reservation as cancelled (but doesn't
     * remove it from the database).
     */
    public function cancelReservation() {
        $canceledId = intval(htmlspecialchars($_GET['id']));
        $canceledReservation = Reservation::getReservationById($canceledId);

        if ($canceledReservation->userId === $this->user->id) {
            $restaurantName = Restaurant::getRestaurantById($canceledReservation->restaurantId);
            $restaurantName = $restaurantName->name;
            if ($canceledReservation->updateStatus(Reservation::STATUS_CANCELLED)) {
                $canceledSuccess = "Your reservation at " . $restaurantName . " was successfully cancelled.";
            } else {
                $canceledSuccess = "There was an error while cancelling your reservation at " . $restaurantName . ".";
            }
        }

        // Reload the page.
        $this->reservations = Reservation::getReservationsByUserId($this->user->id);
        include 'view/page/user_account.php';
    }

}
