<?php


// Functions for User Signup

// Check for empty inputs in signup form
function emptyInputSignup($firstName, $phone, $email, $userHandle, $password, $confirmPassword) {
    $result;
    if (empty($firstName) || empty($phone) || empty($email) || empty($userHandle) ||  empty($password) || empty($confirmPassword) ) {
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
    $sql = "SELECT * FROM users WHERE usersEmail = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header ("Location:../signup.php?error=stmtfailed");
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

// Check if phone number already exists in the database
function phoneExists($conn, $phone) {
    $sql = "SELECT * FROM users WHERE usersPhone = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header ("Location:../signup.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $phone);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        return false;
    }

    mysqli_stmt_close($stmt);
}

// Check if handle name already exists in the database
function userHandleExists($conn, $userHandle) {
    $sql = "SELECT * FROM users WHERE usersHandle = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../signup.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $userHandle);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row; // User handle exists
    } else {
        return false; // User handle does not exist
    }

    mysqli_stmt_close($stmt);
}


// Create new user in the database
function createUser($conn, $firstName, $midName, $lastName, $phone, $email, $userHandle, $password) {

    // Capitalize the first letter of the firstName
    $firstName = ucfirst(strtolower(trim($firstName)));
    $midName = strtolower(trim($midName)); // Optionally standardize middle name
    $lastName = strtolower(trim($lastName)); // Optionally standardize last name
    
    // Concatenate names with proper formatting
    $firstMidLastName = trim($firstName . " " . $midName . " " . $lastName);

    $sql = "INSERT INTO users (usersName, usersPhone, usersEmail, usersHandle, usersPassword) VALUES (?,?,?,?,?);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header ("Location:../signup.php?error=stmtfailed");
        exit();
    }

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Bind and execute the statement
    mysqli_stmt_bind_param($stmt, "sssss", $firstMidLastName, $phone, $email, $userHandle, $hashedPassword);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Redirect to the login page with a success message
    header("Location:../login.php?success=registered");
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
        header("Location:../login.php?error=invaliddetails");
        exit();
    }

    $passwordHashed = $emailExists["usersPassword"];
    $checkPassword = password_verify($password, $passwordHashed);

    if ($checkPassword === false) {
        header('Location:../login.php?error=invaliddetails');
        exit();
    } else if ($checkPassword === true) {
        session_start();

        // Store user data in session
        $fullName = $emailExists["usersName"];
        $_SESSION["id"] = $emailExists["usersId"];
        $_SESSION["email"] = $emailExists["usersEmail"];
        $_SESSION["userHandle"] = $emailExists["usersHandle"];
        $_SESSION["fullName"] = $fullName;
        $_SESSION["firstName"] = explode(' ', trim($fullName))[0]; // Extract first name

        header("Location:../index.php");
        exit();
    }
}


?>