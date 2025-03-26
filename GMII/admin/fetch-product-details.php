<?php
include_once 'includes/admin.dbh.inc.php';

if (isset($_GET['product_id'])) {
    $productId = $_GET['product_id'];

    $stmt = $conn->prepare("SELECT * FROM gmi_db.items WHERE product_id = ?");
    $stmt->bind_param("s", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        echo json_encode($product);
    } else {
        echo json_encode(["error" => "Product not found."]);
    }
    $stmt->close();
}
?>
