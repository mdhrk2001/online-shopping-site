<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GMI - Global Market Items</title>
    <link rel="shortcut icon" href="images/GMI.png" type="image/x-icon">
    <link rel="stylesheet" href="css/signup.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
    
</head>

<?php

include_once 'header.php';

?>

<body>

    <main>

        <h1>
            <span class="col1">G</span><span class="col2">M</span><span class="col3">I</span> Register Form
        </h1>

        <div class="contactdiv">

            <h2>_______Create Account_______</h2>

            <form id="register-form" class="contactform" name="Contacts" action="includes/signup.inc.php" method="post">

                <input class="conts" type="text" id="firstName" name="firstName" placeholder="First Name*" aria-label="Your First Name" style="width: 28.66%;" required>

                <input class="conts" type="text" id="midName" name="midName" placeholder="Mid Name" aria-label="Your Middle Name" style="width: 28.66%;">

                <input class="conts" type="text" id="lastName" name="lastName" placeholder="Last Name" aria-label="Your Last Name" style="width: 28.66%;"><br><br>

                <input class="conts" type="tel" id="phone" name="phone" pattern="(0\d{9})|(\+94\d{9})" title="Please enter a valid phone number in the format '0XXXXXXXXX' or '+94XXXXXXXXX'" placeholder="Phone Number*" aria-label="Phone Number" required><br><br>

                <input class="conts" type="email" id="email" name="email" placeholder="Email*" aria-label="Email" required><br><br>

                <div class="handle-info">
                    <input class="conts" style="width: 94.6%;" type="text" id="userHandle" name="userHandle" placeholder="@User Handle Name*" aria-label="Your User Handle" oninput="updateCharacterCount(this); checkUserHandleAvailability(this);" pattern="^@[A-Za-z0-9_]{4,9}$" title="The handle must start with '@' and include letters, numbers, or underscores (5-10 characters)." maxlength="10" required>

                    <a href="javascript:void(0)" class="handle-info-link" title="Learn more about the User Handle" onclick="togglePopup(event);">
                        <i class="fas fa-info-circle"></i>
                    </a>
                </div>

                <!-- Hidden Popup -->
                <div id="popup" class="popup">
                    
                    <h5>Handle Name Instructions:</h5><br>
                    <p class="popup-info">
                    - The handle name should be unique.<br>
                    - It can include letters, numbers, and underscores.<br>
                    - The first letter must be '@' [Assigned automatically].<br>
                    - Minimum length: 05 characters.<br>
                    - Maximum length: 10 characters.
                    </p>
                </div>

                <div class="handle-status">
                    <div id="userHandleStatus" class="availability-status"></div>
                    <div id="characterCount" class="character-count">[0/10]</div>
                </div>

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
                        case "phonenumberalreadyexists":
                            $errorMessage = "Phone number is already registered!";
                            break;    
                        case "emailalreadyexists":
                            $errorMessage = "Email is already registered!";
                            break;
                        case "handlealreadyexists":
                            $errorMessage = "❌ Handle name is not available!";
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
                <p>Already have a customer account? <a href="login.php">Log In</a> here.</p>
            </div>

        </div>

        <div class="back-div">
            <button id="backbutton" onclick="goBack()">Go Back</button>
        </div>

    </main>


    <script>

        function updateCharacterCount(input) {
            // Add '@' at the beginning if it's not there
            if (!input.value.startsWith('@') && input.value.length > 0) {
                input.value = '@' + input.value.replace(/@/g, ''); // Ensure only one '@'
            }

            const currentLength = input.value.length;

            // Update the character count display
            const characterCountElement = document.getElementById('characterCount');
            characterCountElement.textContent = `[${currentLength}/10]`;
        }


        function checkUserHandleAvailability(input) {
            const userHandle = input.value.trim();
            const statusDiv = document.getElementById('userHandleStatus');

            // Regex for valid user handles: only letters, numbers, underscores, and must start with '@'
            const validHandleRegex = /^@[A-Za-z0-9_]*$/;

            // Allow empty input or '@' only
            if (userHandle === "" || userHandle === "@") {
                statusDiv.textContent = ""; // Clear the status
                return;
            }

            // Check for invalid characters
            if (!validHandleRegex.test(userHandle)) {
                statusDiv.textContent = "❌ Handle contains invalid characters. Only letters, numbers, and underscores are allowed.";
                statusDiv.style.color = "red";
                return;
            }

            // Check if the handle length is sufficient
            if (userHandle.length < 5) {
                statusDiv.textContent = "⚠️ Handle must be at least 5 characters.";
                statusDiv.style.color = "red";
                return;
            }

            // Make an AJAX request only for valid handles
            if (userHandle.length > 1) { // Avoid querying for empty or '@'
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "includes/check_userhandle.inc.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        statusDiv.textContent = xhr.responseText.trim();
                        statusDiv.style.color = xhr.responseText.includes("available") ? "green" : "red";
                    }
                };

                xhr.send("userHandle=" + encodeURIComponent(userHandle));
            } else {
                statusDiv.textContent = ""; // Clear the status if input is too short
            }
        }



        
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


        function togglePopup(event) {
            const popup = document.getElementById('popup');
            if (popup.style.display === 'none' || popup.style.display === '') {
            popup.style.display = 'block';
            } else {
            popup.style.display = 'none';
            }
        }

        // Optional: Close the popup if the user clicks outside of it
        document.addEventListener('click', function(event) {
            const popup = document.getElementById('popup');
            if (!event.target.closest('#popup') && !event.target.closest('a')) {
            popup.style.display = 'none';
            }
        });
        
    </script>


<?php include_once 'footer.php'; ?>

</body>

</html>