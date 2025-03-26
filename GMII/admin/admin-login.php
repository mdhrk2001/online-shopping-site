<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GMI - Admin Dashboard Login</title>
    <link rel="shortcut icon" href="../images/GMI.png" type="image/x-icon">
    <link rel="stylesheet" href="css/admin-login.css">
</head>

<?php

include_once 'admin-header.php';

?>

<body>

    <main>

        <h2>
            <span class="col1">G</span><span class="col2">M</span><span class="col3">I</span> Login Form
        </h2>

        <div class="login-container">

            <h3>________Login Account________</h3>

            <?php
                if (isset($_GET["success"]) && htmlspecialchars($_GET["success"]) === "registered") {
                    echo '<div id="successMessage" class="success">✅ Your admin account has been created successfully! &nbsp;Now login here.</div>';
                }
            ?>

            <form id="login-form" class="loginform" action="includes/admin.login.inc.php" method="post">

                <label for="loginEmail">Email:</label><br>

                <input class="inpt" type="email" id="loginEmail" name="email" placeholder="Enter your email" required><br><br>

                <label for="loginPassword">Password:</label><br>

                <input class="inpt" type="password" id="loginPassword" name="password" placeholder="Enter your password" required>

                <span class="toggle-password" onclick="togglePasswordVisibility('loginPassword', this)">👁️</span>

                <?php
                if (isset($_GET["error"])) {

                    $errorMessage = '';

                    switch ($_GET["error"]) {

                        case "emptyinput":
                            $errorMessage = "Please fill in all fields!";
                            break;
                        case "invaliddetails":
                            $errorMessage = "❌ Incorrect email or password!";
                            break;
                        case "stmtfailed":
                            $errorMessage = "❌ Something went wrong. Please contact support.";
                            break;
                    }

                    if (!empty($errorMessage)) {
                        echo '<div id="loginErrorMessage" class="error">' . htmlspecialchars($errorMessage) . '</div>';
                    }

                }
                ?>

                <button name="submit" type="submit" id="loginButton">LOGIN</button>

            </form>

            <div class="sign-ref">
                <p>New admin? <a href="admin-signup.php">Sign Up</a> here.</p>
            </div>

        </div>

        <div class="back-div">
            <button id="backbutton" onclick="goBack()">Go Back</button>
        </div>

    </main>

    
    <script  src="js/admin-login.js"></script>


</body>

</html>
