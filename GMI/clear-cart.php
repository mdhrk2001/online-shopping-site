<?php
session_start();
include_once 'includes/dbh.inc.php';

if (!isset($_SESSION['id'])) {
    echo "Unauthorized access.";
    exit();
}

$usersId = $_SESSION['id'];

$clearSql = "DELETE FROM gmi_db.cart WHERE usersId = ?";
$stmt = mysqli_prepare($conn, $clearSql);
mysqli_stmt_bind_param($stmt, "i", $usersId);

if (mysqli_stmt_execute($stmt)) {
    echo "Cart cleared successfully!";
} else {
    echo "Failed to clear cart.";
}
mysqli_stmt_close($stmt);
?>
