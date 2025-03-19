
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


    <script>
        function searchItems() {
            const searchInput = document.getElementById('search-input').value.toLowerCase();
            const productCards = document.querySelectorAll('.product-card');

            productCards.forEach(card => {
                const productName = card.querySelector('h2').textContent.toLowerCase();
                if (productName.includes(searchInput)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });

            document.getElementById('search-input').value = '';
        }

        function filterByCategory() {
            const selectedCategory = document.getElementById('category-dropdown').value;
            const productCards = document.querySelectorAll('.product-card');

            productCards.forEach(card => {
                if (selectedCategory === 'all' || card.dataset.category === selectedCategory) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function addToCart(name, price, imgSrc) {
            fetch('add-to-cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ name, price, imgSrc })
            })
            .then(response => response.text())
            .then(data => {
                if (data.includes("Error: User is not logged in")) {
                    alert("Please log in to add items to the cart.");
                    window.location.href = "login.php"; // Redirect to login page
                } else {
                    alert(data); // Display success or error message
                }
            })
            .catch(error => console.error('Error:', error));
        }


    </script>

    <?php include_once 'footer.php'; ?>
</body>

</html>
