
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GMI-Global Market Items</title>
    <link rel="shortcut icon" href="images/GMI.png" type="image/x-icon">
    <link rel="stylesheet" href="css/items.css">
</head>

<?php

include_once 'header.php';
include_once 'includes/dbh.inc.php';

?>

<body>

    <section class="filter-bar">
        <select id="category-dropdown" onchange="filterByCategory()">
            <option value="all">All Categories</option>
            <option value="electronics">Electronics</option>
            <option value="fashion">Fashion</option>
            <option value="home">Home</option>
            <option value="beauty">Beauty</option>
        </select>
        <input type="text" id="search-input" placeholder="Search for items...">
        <button onclick="searchItems()">Search</button>
    </section>

    <main>
        <section class="product-list" id="product-list">
            <?php
            // Fetch all products from the database
            $sql = "SELECT * FROM gmi_db.items";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $id = htmlspecialchars($row['product_id']);
                    $name = htmlspecialchars($row['p_name']);
                    $price = htmlspecialchars($row['price']);
                    
                    // Normalize photo path if it starts with ../
                    $photoPath = $row['photo_path'] ? htmlspecialchars(str_replace('../', '', $row['photo_path'])) : 'images/products/default.png';
                    
                    $category = htmlspecialchars($row['category']);

                    echo "
                        <div class='product-card' data-category='$category'>
                            <img src='$photoPath' alt='$name'>
                            <h2>$name - $id</h2>
                            <p>Price: LKR.$price</p>
                            <button onclick=\"addToCart('$name', 'LKR.$price', '$photoPath')\">Add to Cart</button>
                            <a class='detail-span' href='product-details.php?id=$id'>More Details...</a>
                        </div>
                    ";
                }
            } else {
                echo "<div class='no-items'>No items here.</div>";
            }
            ?>
            <div id="cart-message" class="cart-message" aria-live="assertive"></div>
        </section>
    </main>


    <script src="js/items.js"></script>


    <?php include_once 'footer.php'; ?>
</body>

</html>
