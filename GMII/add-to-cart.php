<?php session_start();  // Start the session to access user data

include_once 'includes/dbh.inc.php';

// Get the JSON data sent from JavaScript
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['name'], $data['price'], $data['imgSrc'])) {
    $name = $data['name'];
    $price = floatval(str_replace('LKR.', '', $data['price'])); // Convert price to numeric
    $imgSrc = $data['imgSrc'];

    // ✅ Retrieve logged-in user's ID
    if (isset($_SESSION['id'])) {
        $usersId = $_SESSION['id']; // Get user ID from session
    } else {
        echo "Error: User is not logged in.";
        exit;
    }

    // Retrieve product ID based on the name
    $sqlProduct = "SELECT product_id FROM items WHERE p_name = ?";
    $stmtProduct = mysqli_prepare($conn, $sqlProduct);

    if ($stmtProduct) {
        mysqli_stmt_bind_param($stmtProduct, "s", $name);
        mysqli_stmt_execute($stmtProduct);
        mysqli_stmt_bind_result($stmtProduct, $productId);
        mysqli_stmt_fetch($stmtProduct);
        mysqli_stmt_close($stmtProduct);

        if ($productId) {
            // Check if the product is already in the cart
            $sqlCheckCart = "SELECT COUNT(*) FROM cart WHERE product_id = ? AND usersId = ?";
            $stmtCheckCart = mysqli_prepare($conn, $sqlCheckCart);
            mysqli_stmt_bind_param($stmtCheckCart, "si", $productId, $usersId);
            mysqli_stmt_execute($stmtCheckCart);
            mysqli_stmt_bind_result($stmtCheckCart, $cartCount);
            mysqli_stmt_fetch($stmtCheckCart);
            mysqli_stmt_close($stmtCheckCart);

            if ($cartCount > 0) {
                echo "Error: This product is already in the cart.";
                exit;
            }

            // Insert the product into the cart table
            $sqlInsert = "INSERT INTO cart (product_id, usersId) VALUES (?, ?)";
            $stmtInsert = mysqli_prepare($conn, $sqlInsert);

            if ($stmtInsert) {
                mysqli_stmt_bind_param($stmtInsert, "si", $productId, $usersId);
                if (mysqli_stmt_execute($stmtInsert)) {
                    echo "Product added to cart successfully.";
                } else {
                    echo "Error: Could not add the product to the cart.";
                }
                mysqli_stmt_close($stmtInsert);
            }
        } else {
            echo "Error: Product not found in the items table.";
        }
    } else {
        echo "Error: Failed to prepare the SQL statement.";
    }
} else {
    echo "Error: Invalid data.";
}

mysqli_close($conn);
?>
