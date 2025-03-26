<!DOCTYPE html>
<html lang="en">
    
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GMI-Global Market Items</title>
    <link rel="shortcut icon" href="images/GMI.png" type="image/x-icon">
    <link rel="stylesheet" href="css/main.css">

</head>

<?php

include_once 'header.php'

?>

<body>

    <!-- Search Bar -->
    <section class="search-bar">

        <input type="text" id="search-input" placeholder="Search for items...">
        <button onclick="searchItems()">Search</button>

    </section>

    <main>

        <p>Hello

            <?php
            if (isset($_SESSION["firstName"])) {
                echo htmlspecialchars($_SESSION["firstName"]) . ',';
            } else {
                echo 'User,';
            }
            ?>
            
        </p>

        <h1>Welcome to Global Market Items !</h1>

        <img src="images/Global_Market_Items.png" alt="GMI Cover Logo">

    </main>
    

<?php include_once 'footer.php' ?>    

</body>

</html>

    