<?php
include_once 'includes/admin.dbh.inc.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'];
    $name = $_POST['product-name'];
    $price = $_POST['product-price'];
    $description = $_POST['product-description'];
    $category = $_POST['product-category'];

    // Handle main product photo upload (if new photo is uploaded)
    $photo = $_FILES['product-photo']['name'];
    $photoPath = "../images/products/" . $photo;
    if ($photo) {
        move_uploaded_file($_FILES['product-photo']['tmp_name'], $photoPath);
    } else {
        // Keep the existing photo path
        $photoPath = null;
    }

    // Handle featured photos upload (if new photos are uploaded)
    $featuredPhotos = [];
    if (isset($_FILES['featured-photo'])) {
        foreach ($_FILES['featured-photo']['name'] as $index => $fileName) {
            $targetPath = "../images/featured/" . $fileName;
            move_uploaded_file($_FILES['featured-photo']['tmp_name'][$index], $targetPath);
            $featuredPhotos[] = $targetPath;
        }
    }
    $featuredPhotosSerialized = serialize($featuredPhotos);

    // Update the product in the database
    $stmt = $conn->prepare("UPDATE gmi_db.items SET p_name = ?, price = ?, p_description = ?, category = ?, photo_path = IFNULL(?, photo_path), featured_photos = ? WHERE product_id = ?");
    $stmt->bind_param("sdsssss", $name, $price, $description, $category, $photoPath, $featuredPhotosSerialized, $productId);
    $stmt->execute();
    $stmt->close();

    echo "Product updated successfully.";
}
?>
