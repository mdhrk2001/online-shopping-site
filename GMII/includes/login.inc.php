<?php

if (isset($_POST["submit"])) {
    // Sanitize input
    $username = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $password = htmlspecialchars($_POST["password"]);

    // Include required files
    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    // Check for empty fields
    if (emptyInputLogin($username, $password) !== false) {
        header('Location:../login.php?error=emptyinput'); // Redirect if fields are empty
        exit();
    }

    // Log the user in
    loginUser($conn, $username, $password);
} else {
    // Redirect if script accessed without form submission
    header('Location:../login.php');
    exit();
}

?>