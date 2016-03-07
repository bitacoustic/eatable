<?php

/**
 * index.php
 * 
 * Router file for eatable restaurant reservation web application.
 * 
 * @author Sebastian Babb
 * @version SVN:$id:$
 */
/*
 * Check what type of request was sent.  A post request indicates
 * a login or registration operation.
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    /*
     * A post request was recieved. Check the form name to determine
     * if its a new user registering or an existing user logging in.
     */

    // Both GET and POST parameters were passed. The GET parameters
    // specify the controller and action, the POST parameters contain
    // the information to be processed.
    if (isset($_GET['controller'])) {
        // Store the controller and action parameters.
        $controller = htmlspecialchars($_GET['controller']);
        $action = htmlspecialchars($_GET['action']);

        // Build the controller file's path .
        $controllerPath = 'controller/' . $controller . 'Controller.php';
        // Check that the specified controller exists.
        if (file_exists($controllerPath)) {
            // Include the controller.
            include_once($controllerPath);
            // Build the controller function.
            $controllerFunction = $controller . 'Controller';
            // Create an object of the controller.
            $controller = new $controllerFunction();
            // Call the action using the controller object.
            $controller->{ $action }();
        } else {
            // There is no such controller, load error page.
            echo "Controller does not exist";
        }
    }
    // New user registration.
    elseif (isset($_POST['form-name']) && $_POST['form-name'] == 'registration-form') {
        // A new user registering, load and call the registration controller.
        include_once("controller/RegistrationController.php");
        $registrationController = new RegistrationController();
        $registrationController->registerNewUser();
    }
    // An existing user is logging in, load and call the login controller.
    else {
        include_once("controller/LoginController.php");
        $loginController = new LoginController();
        $loginController->invoke();
    }
}
/*
 * A get request was sent, determine the controller and action.  
 */ else if (isset($_GET['controller']) && isset($_GET['action'])) {
    // Store the controller and action parameters.
    $controller = htmlspecialchars($_GET['controller']);
    $action = htmlspecialchars($_GET['action']);

    // Build the controller file's path .
    $controllerPath = 'controller/' . $controller . 'Controller.php';
    // Check that the specified controller exists.
    if (file_exists($controllerPath)) {
        // Include the controller.
        include_once($controllerPath);
        // Build the controller function.
        $controllerFunction = $controller . 'Controller';
        // Create an object of the controller.
        $controller = new $controllerFunction();
        // Call the action using the controller object.
        $controller->{ $action }();
    } else {
        // There is no such controller, load error page.
        echo "Controller does not exist";
    }
}
// No controller or action specified, or an invalid action was sent.
else {
    include_once("controller/LandingController.php");
    $landingController = new LandingController();
    $landingController->invoke();
}
