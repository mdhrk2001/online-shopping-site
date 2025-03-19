<?php

// Functions for User Signup

// Check for empty inputs in signup form
function emptyInputSignup($email, $password, $confirmPassword) {
    $result;
    if (empty($email) || empty($password) || empty($confirmPassword) ) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

// Validate email format
function invalidEmail($email) {
    $result;
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

// Check if passwords match
function passwordMatch($password, $confirmPassword) {
    $result;
    if ($password != $confirmPassword) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

// Check if email already exists in the database
function emailExists($conn, $email) {
    $sql = "SELECT * FROM admins WHERE adminsEmail = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header ("Location:../admin-signup.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        return false;
    }

    mysqli_stmt_close($stmt);
}


// Create new user in the database
function createUser($conn, $email, $password) {

    $sql = "INSERT INTO admins (adminsEmail, adminsPassword) VALUES (?,?);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header ("Location:../admin-signup.php?error=stmtfailed");
        exit();
    }

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Bind and execute the statement
    mysqli_stmt_bind_param($stmt, "ss", $email, $hashedPassword);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Redirect to the login page with a success message
    header("Location:../admin-login.php?success=registered");
    exit();
}


// Functions for User Login

// Check for empty inputs in login form
function emptyInputLogin($username, $password) {
    $result;
    if (empty($username) || empty($password)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

// Handle login process
function loginUser($conn, $username, $password) {
    $emailExists = emailExists($conn, $username);
    if ($emailExists === false) {
        header("Location:../admin-login.php?error=invaliddetails");
        exit();
    }

    $passwordHashed = $emailExists["adminsPassword"];
    $checkPassword = password_verify($password, $passwordHashed);

    if ($checkPassword === false) {
        header('Location:../admin-login.php?error=invaliddetails');
        exit();
    } else if ($checkPassword === true) {
        session_start();

        // Store user data in session
        $_SESSION["id"] = $emailExists["adminsId"];
        $_SESSION["email"] = $emailExists["adminsEmail"];

        header("Location:../admin-dashboard.php");
        exit();
    }
}


?>