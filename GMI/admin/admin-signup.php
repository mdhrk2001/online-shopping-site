<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GMI - Admin Dashboard Signup</title>
    <link rel="shortcut icon" href="../images/GMI.png" type="image/x-icon">
    <link rel="stylesheet" href="css/admin-signup.css">

</head>

<?php

include_once 'admin-header.php';

?>

<body>

    <main>

        <h2>
            <span class="col1">G</span><span class="col2">M</span><span class="col3">I</span> Register Form
        </h2>

        <div class="contactdiv">

            <h3>_______Create Account_______</h3>

            <form id="register-form" class="contactform" name="Contacts" action="includes/admin.signup.inc.php" method="post">

                <input class="conts" type="email" id="email" name="email" placeholder="Email*" aria-label="Email" required><br><br>

                <input class="conts" type="password" id="password" name="password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*]).{6,}$" title="Password must be at least 6 characters, include at least one lowercase letter, one uppercase letter, one number, and one special character" placeholder="Enter Password*" aria-label="Password" style="width: 43.5%;" required>               
                
                <input class="conts" type="password" id="confirmPassword" name="confirmPassword" placeholder="Re-enter Password*" aria-label="Re-enter Password" style="width: 43.5%;" required>
                
                <span class="toggle-password-left" onclick="togglePasswordVisibility('password', this)">👁️</span>
                
                <span class="toggle-password-right" onclick="togglePasswordVisibility('confirmPassword', this)">👁️</span>

                <!-- Error messages -->
                <?php
                if (isset($_GET["error"])) {

                    $errorMessage = '';

                    switch ($_GET["error"]) {

                        case "emptyinput":
                            $errorMessage = "Please fill in all fields!";
                            break;
                        case "invalidemail":
                            $errorMessage = "❌ Invalid Email!";
                            break;    
                        case "emailalreadyexists":
                            $errorMessage = "Email is already registered!";
                            break;    
                        case "passwordsnotmatch":
                            $errorMessage = "❌ Passwords do not match!";
                            break;
                        case "stmtfailed":
                            $errorMessage = "❌ Something went wrong. Please contact support.";
                            break;
                    }

                    if (!empty($errorMessage)) {
                        echo '<div id="errorMessage" class="error">' . htmlspecialchars($errorMessage) . '</div>';
                    }

                }
                ?>

                <div id="subdiv">
                    <input type="submit" name="submit" value="REGISTER NOW" id="subbutton">
                </div>

            </form>

            <div class="log-ref">
                <p>Already have an admin account? <a href="admin-login.php">Log In</a> here.</p>
            </div>

        </div>

        <div class="back-div">
            <button id="backbutton" onclick="goBack()">Go Back</button>
        </div>

    </main>


    <script>
        
        function togglePasswordVisibility(passwordFieldId, toggleIcon) {
            const passwordField = document.getElementById(passwordFieldId);
            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.textContent = "😵";
            } else {
                passwordField.type = "password";
                toggleIcon.textContent = "👁️";
            }
        }

        
        const errorMessage = document.getElementById("errorMessage");
        
        setTimeout(() => {
        errorMessage.style.visibility = "hidden";
        }, 5000);

        
        function goBack() {
            window.history.back();
        }
        
    </script>


</body>

</html>