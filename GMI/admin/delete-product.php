<?php
include_once 'includes/admin.dbh.inc.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['product_id'])) {
    $productId = $data['product_id'];

    // Prepare the SQL query to delete the product
    $stmt = $conn->prepare("DELETE FROM gmi_db.items WHERE product_id = ?");
    $stmt->bind_param("s", $productId);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Product deleted successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to delete product."]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid product ID."]);
}
?>
