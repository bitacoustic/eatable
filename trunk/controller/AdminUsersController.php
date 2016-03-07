<?php

include_once('model/User.php');
include_once('model/Administrator.php');
include_once('model/Employee.php');
include_once('model/Owner.php');
include_once('model/Host.php');

include_once('model/Restaurant.php');

class AdminUsersController {

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
            $sort = NIL;
            if (isset($_GET['sort'])) {
                $sort = htmlspecialchars($_GET['sort']);
                switch ($sort) {
                    case 'id':
                    case 'fname':
                    case 'lname':
                    case 'email':
                    case 'session_id':
                        break;
                    default:
                        $sort = 'id';
                }
            }

            $users = User::getAllUsers($sort);
            include 'view/page/admin_users.php';
        }
    }

    public function removeUser() {
        if (!($this->user->verify())) {
            include 'view/page/landing_page.php';
        } else {
            if (isset($_GET['id'])) {
                $removedId = htmlspecialchars($_GET['id']);
                $removedIdSuccess = User::removeById($removedId);
            }
            $users = User::getAllUsers();
            include 'view/page/admin_users.php';
        }
    }

    public function updatePermissions() {
        if (!($this->user->verify())) {
            include 'view/page/landing_page.php';
        } else {
            if (isset($_POST['id'])) {
                $id = intval($_POST['id']);
                $updateUser = User::getUserById($id);

                if (isset($_POST['roles'])) {
                    $roles = $_POST['roles'];
                } else {
                    $roles = array();
                }

                $userIsAdmin = $updateUser->isAdmin();
                if (in_array("admin", $roles) && !$userIsAdmin) {
                    Administrator::setAdministrator($updateUser->id);
                } elseif (!in_array("admin", $roles) && $userIsAdmin) {
                    Administrator::unsetAdministrator($updateUser->id);
                }

                $userIsOwner = $updateUser->isOwner();
                $userIsHost = $updateUser->isHost();
                if (in_array("employee", $roles)) {
                    if (isset($_POST['restaurant_id'])) {
                        // Unsets employee permissions, and by extension,
                        // host and/or owner permissions.
                        $updateEmployee = Employee::getEmployeeById($updateUser->id);
                        if (is_object($updateEmployee)) {
                            Employee::unsetEmployee($updateUser->id, $updateEmployee->restaurantId);
                        }

                        $newRestaurantId = intval($_POST['restaurant_id']);
                        Employee::setEmployee($updateUser->id, $newRestaurantId);

                        if (in_array("owner", $roles) && !$userIsOwner) {
                            Owner::setOwner($updateUser->id);
                        } elseif (!in_array("owner", $roles) && $userIsOwner) {
                            Owner::unsetOwner($updateUser->id);
                        }

                        if (in_array("host", $roles) && !$userIsHost) {
                            Host::setHost($updateUser->id);
                        } elseif (!in_array("host", $roles) && $userIsHost) {
                            Host::unsetHost($updateUser->id);
                        }
                    }
                } else {
                    // Unsets employee permissions, and by extension,
                    // host and/or owner permissions.
                        $updateEmployee = Employee::getEmployeeById($updateUser->id);
                        if (is_object($updateEmployee)) {
                            Employee::unsetEmployee($updateUser->id, $updateEmployee->restaurantId);
                        }
                }
            }

            $users = User::getAllUsers();
            include 'view/page/admin_users.php';
        }
    }

}
