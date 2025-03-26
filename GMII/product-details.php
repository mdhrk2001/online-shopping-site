<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details - GMI</title>
    <link rel="shortcut icon" href="images/GMI.png" type="image/x-icon">
    <link rel="stylesheet" href="css/product-details.css">
</head>

<body>

    <?php session_start();  // Start the session to access user data
    
    include_once 'includes/dbh.inc.php';

    // Get the product ID from the URL
    $productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Fetch product details from the database
    $sql = "SELECT * FROM gmi_db.items WHERE product_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $productId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $product = mysqli_fetch_assoc($result);
    ?>

    <header>
        <?php
        $previousPage = $_SERVER['HTTP_REFERER'] ?? '';
        
        if (strpos($previousPage, 'items.php') !== false) {
            echo '<a href="items.php">Back to Products</a>';
        } elseif (strpos($previousPage, 'cart.php') !== false) {
            echo '<a href="cart.php">Back to Cart</a>';
        } else {
            echo '<a href="items.php">Back to Products</a>'; // Default fallback
        }
        ?>
    </header>

    <main>
        <?php if ($product): ?>
        <div class="product-details">
            <div class="main-image">
                <img id="product-image" src="<?php echo htmlspecialchars(str_replace('../', '', $product['photo_path'] ?? 'images/products/default.png')); ?>" alt="Product Image">
            </div>

            <div class="featured-slideshow">
                <div class="slideshow-container" id="slideshow-container">
                    <?php
                    // Deserialize the data stored in 'featured_photos'
                    $featuredPhotos = unserialize($product['featured_photos'] ?? '');

                    if (!empty($featuredPhotos) && is_array($featuredPhotos)) {
                        foreach ($featuredPhotos as $index => $photo) {
                            // Sanitize and correct the photo path
                            $photoPath = htmlspecialchars(str_replace('../', '', $photo));
                            echo "<div class='slide'><img src='$photoPath' alt='Featured Photo " . ($index + 1) . "'></div>";
                        }
                    } else {
                        echo "<p>No featured photos available.</p>";
                    }
                    ?>
                </div>
                <div class="slideshow-navigation">
                    <span id="prev" onclick="prevSlide()">&#10094;</span>
                    <span id="next" onclick="nextSlide()">&#10095;</span>
                </div>
            </div>

            <div class="product-info">
                <h1 id="product-name"><?php echo htmlspecialchars($product['p_name']); ?></h1>
                <p id="product-id">ID: <?php echo htmlspecialchars($product['product_id']); ?></p>
                <p id="product-price">Price: LKR <?php echo htmlspecialchars($product['price']); ?></p>
                <p id="product-description">Description: <?php echo htmlspecialchars($product['p_description']); ?></p>
                <button onclick="addToCart()">Add to Cart</button>
            </div>
        </div>
        <?php else: ?>
        <div class="no-product">
            <p>Product not found.</p>
        </div>
        <?php endif; ?>

        <div id="cart-message" class="cart-message"></div>
    </main>


    <script src="js/product-details.js"></script>


</body>

</html>
