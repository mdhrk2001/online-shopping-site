<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GMI - Shopping Cart</title>
    <link rel="shortcut icon" href="images/GMI.png" type="image/x-icon">
    <link rel="stylesheet" href="css/cart.css">
</head>

<?php

include_once 'header.php';
include_once 'includes/dbh.inc.php';

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");  // Redirect to login if not logged in
    exit();
}

$usersId = $_SESSION['id'];  // Get the logged-in user's ID
?>

<body>
    <h1>Your Shopping Cart</h1>

    <main class="cart-list">
        <?php
        // Retrieve cart items for the logged-in user only
        $cartSql = "SELECT c.cart_id, c.product_id, p.p_name, p.price, p.photo_path 
                    FROM gmi_db.cart c 
                    INNER JOIN gmi_db.items p 
                    ON c.product_id = p.product_id
                    WHERE c.usersId = ?"; // Filter by user ID

        $stmt = mysqli_prepare($conn, $cartSql);
        mysqli_stmt_bind_param($stmt, "i", $usersId);
        mysqli_stmt_execute($stmt);
        $cartResult = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($cartResult) > 0) {
            while ($cartItem = mysqli_fetch_assoc($cartResult)) {
                $cartId = htmlspecialchars($cartItem['cart_id']);
                $productId = htmlspecialchars($cartItem['product_id']);
                $productName = htmlspecialchars($cartItem['p_name']);
                $productPrice = htmlspecialchars($cartItem['price']);
                $photoPath = $cartItem['photo_path'] ? htmlspecialchars(str_replace('../', '', $cartItem['photo_path'])) : 'images/products/default.png';

                echo "
                    <div class='cart-item'>
                        <img src='" . htmlspecialchars($photoPath, ENT_QUOTES, 'UTF-8') . "' alt='" . htmlspecialchars($productName, ENT_QUOTES, 'UTF-8') . "'>
                        <a href='product-details.php?id=" . htmlspecialchars($productId, ENT_QUOTES, 'UTF-8') . "' class='cart-link'>
                            <p>Product: " . htmlspecialchars($productName, ENT_QUOTES, 'UTF-8') . "</p>
                        </a>
                        <p class='p-price'>Price: LKR." . htmlspecialchars($productPrice, ENT_QUOTES, 'UTF-8') . "</p>
                        <button onclick=\"removeFromCart('" . htmlspecialchars($cartId, ENT_QUOTES, 'UTF-8') . "')\">Remove</button>
                    </div>
                ";
            }
        } else {
            echo "<p>Your cart is empty.</p>";
        }

        mysqli_stmt_close($stmt);
        ?>
    </main>

    <section>
        <button onclick="clearCart()">Clear Cart</button>
    </section>

    <script>
        function removeFromCart(cartId) {
            fetch(`remove-from-cart.php?id=${cartId}`, { method: 'GET' })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    location.reload(); // Reload the page to reflect the updated cart
                })
                .catch(error => console.error('Error:', error));
        }

        function clearCart() {
            fetch('clear-cart.php', { method: 'GET' })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    location.reload(); // Reload the page to reflect the updated cart
                })
                .catch(error => console.error('Error:', error));
        }
    </script>

    <?php include_once 'footer.php'; ?>
</body>

</html>
