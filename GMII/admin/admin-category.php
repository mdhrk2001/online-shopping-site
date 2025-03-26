<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GMI - Admin Dashboard</title>
    <link rel="shortcut icon" href="../images/GMI.png" type="image/x-icon">
    <link rel="stylesheet" href="css/admin-category.css">

</head>

<?php

include_once 'admin-header.php'

?>

<body>
    
    <main>
        
        <section id="categories">

            <h2>Category Management</h2>

            <!-- Form to Add/Edit Categories -->
            <form id="category-form">

                <label for="category-name">Category Name:</label>
                <input type="text" id="category-name" name="category-name" placeholder="Enter category name" required>
                <button class="category-btn" type="submit">Add Category</button>
                <button class="category-btn" type="reset">Reset</button>

            </form>

            <!-- List of Existing Categories -->
            <h3>Existing Categories</h3>

            <ul id="category-list">
                <li>Electronics</li>
                <li>Fashion</li>
                <li>Home</li>
                <li>Health</li>
                <!-- Dynamically loaded categories will appear below -->
            </ul>

        </section>

    </main>


    <script  src="js/admin-category.js"></script>


</body>

</html>
