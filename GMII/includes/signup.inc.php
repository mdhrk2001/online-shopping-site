<?php


if (isset($_POST["submit"])) {
    $firstName = $_POST["firstName"];
    $midName = $_POST["midName"];
    $lastName = $_POST["lastName"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $userHandle = $_POST["userHandle"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    $emptyInput = emptyInputSignup($firstName, $phone, $email, $userHandle, $password, $confirmPassword);
    $invalidEmail = invalidEmail($email);
    $passwordMatch = passwordMatch($password, $confirmPassword);
    $emailExists = emailExists($conn, $email);
    $phoneExists = phoneExists($conn, $phone);
    $userHandleExists = userHandleExists($conn, $userHandle);


    // Validate inputs

    if ($emptyInput !== false) {
        header("Location:../signup.php?error=emptyinput");
        exit();
    }

    if ($invalidEmail !== false) {
        header("Location:../signup.php?error=invalidemail");
        exit();
    }

    if ($passwordMatch !== false) {
        header("Location:../signup.php?error=passwordsnotmatch");
        exit();
    }

    if ($emailExists !== false) {
        header("Location:../signup.php?error=emailalreadyexists");
        exit();
    }

    if ($phoneExists !== false) {
        header("Location:../signup.php?error=phonenumberalreadyexists");
        exit();
    }

    if ($userHandleExists !== false) {
        header("Location:../signup.php?error=handlealreadyexists");
        exit();
    }

    // Create user if all validations pass
    createUser($conn, $firstName, $midName, $lastName, $phone, $email, $userHandle, $password);

} else {
    header('Location:../login.php');
    exit();
}


?>