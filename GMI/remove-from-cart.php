<?php
session_start();
include_once 'includes/dbh.inc.php';

if (!isset($_SESSION['id'])) {
    echo "Unauthorized access.";
    exit();
}

$usersId = $_SESSION['id'];

if (isset($_GET['id'])) {
    $cartId = intval($_GET['id']);

    // Ensure the item belongs to the logged-in user
    $deleteSql = "DELETE FROM gmi_db.cart WHERE cart_id = ? AND usersId = ?";
    $stmt = mysqli_prepare($conn, $deleteSql);
    mysqli_stmt_bind_param($stmt, "ii", $cartId, $usersId);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "Item removed successfully!";
    } else {
        echo "Failed to remove item.";
    }
    mysqli_stmt_close($stmt);
} else {
    echo "Invalid request.";
}
?>
