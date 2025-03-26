<?php


if (isset($_POST["submit"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];

    require_once 'admin.dbh.inc.php';
    require_once 'admin.functions.inc.php';

    $emptyInput = emptyInputSignup($email, $password, $confirmPassword);
    $invalidEmail = invalidEmail($email);
    $passwordMatch = passwordMatch($password, $confirmPassword);
    $emailExists = emailExists($conn, $email);


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

    // Create user if all validations pass
    createUser($conn, $email, $password);

} else {
    header('Location:../admin-login.php');
    exit();
}


?>