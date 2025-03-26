<?php
// Include database connection
include_once 'includes/admin.dbh.inc.php';

// Process the form data
if (isset($_POST['submit_button'])) {
    $name = ucfirst($_POST['product-name']);
    $id = ucfirst($_POST['product-id']);
    $price = $_POST['product-price'];
    $description = $_POST['product-description'];
    $category = $_POST['product-category'];
    $photo = $_FILES['product-photo']['name'];
    $photoPath = "../images/products/" . $photo;

    if (!empty($_FILES['product-photo']['tmp_name'])) {
        move_uploaded_file($_FILES['product-photo']['tmp_name'], $photoPath);
    }

    // Handle featured photos
    $featuredPhotos = [];
    if (!empty($_FILES['featured-photo']['name'][0])) {
        foreach ($_FILES['featured-photo']['name'] as $index => $fileName) {
            $targetPath = "../images/featured/" . $fileName;
            move_uploaded_file($_FILES['featured-photo']['tmp_name'][$index], $targetPath);
            $featuredPhotos[] = $targetPath;
        }
    }
    $featuredPhotosSerialized = serialize($featuredPhotos);

    // Insert or update the product
    $checkQuery = $conn->prepare("SELECT COUNT(*) FROM gmi_db.items WHERE product_id = ?");
    $checkQuery->bind_param("s", $id);
    $checkQuery->execute();
    $checkQuery->bind_result($count);
    $checkQuery->fetch();
    $checkQuery->close();

    if ($count > 0) {
        $stmt = $conn->prepare("UPDATE gmi_db.items SET p_name = ?, price = ?, p_description = ?, category = ?, photo_path = ?, featured_photos = ? WHERE product_id = ?");
        $stmt->bind_param("sdsssss", $name, $price, $description, $category, $photoPath, $featuredPhotosSerialized, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO gmi_db.items (product_id, p_name, price, p_description, category, photo_path, featured_photos) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdssss", $id, $name, $price, $description, $category, $photoPath, $featuredPhotosSerialized);
    }
    $stmt->execute();
    $stmt->close();

    // Redirect to the same page to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch product data
$result = $conn->query("SELECT * FROM gmi_db.items");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GMI - Admin Dashboard</title>
    <link rel="shortcut icon" href="../images/GMI.png" type="image/x-icon">
    <link rel="stylesheet" href="css/admin-dashboard.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</head>

<?php include_once 'admin-header.php'; ?>

<body>
    <main>
        <section id="products">
            <h2>Product Management</h2>
            <form id="add-product-form" method="POST" enctype="multipart/form-data">
                <label for="product-name">Product Name:</label> <small>Characters limit: 45</small>
                <input type="text" id="product-name" name="product-name" placeholder="Enter product name" maxlength="45" required>

                <label for="product-id">Product ID:</label>
                <input type="text" id="product-id" name="product-id" required>

                <label for="product-price">Price:</label>
                <input type="number" id="product-price" name="product-price" required>

                <label for="product-description">Description:</label>
                <textarea id="product-description" name="product-description"></textarea>

                <label for="product-category">Category:</label>
                <select id="product-category" name="product-category" required>
                    <option value="">Select a category</option>
                    <option value="electronics">Electronics</option>
                    <option value="fashion">Fashion</option>
                    <option value="home">Home</option>
                    <option value="health">Health</option>
                </select>

                <label for="product-photo">Main Photo:</label> <small>Recommended size: 800x800px</small>
                <input type="file" id="product-photo" name="product-photo" accept="image/*" required>

                <label for="featured-photo">Featured Photos:</label>
                <input type="file" id="featured-photo" name="featured-photo[]" accept="image/*" multiple>

                <button type="submit" name="submit_button" class="add-product-btn">Add Product</button>
            </form>
        </section>

        <section id="product-list-section">
            <h2>Product List</h2>
            <div id="div1">
                <input type="text" id="search-box" placeholder="Search by product name/id">
                <div id="div2">
                    <label for="filter-category">Filter by Category:</label>
                    <select id="filter-category">
                        <option value="all">All Categories</option>
                        <option value="electronics">Electronics</option>
                        <option value="fashion">Fashion</option>
                        <option value="home">Home</option>
                        <option value="health">Health</option>
                    </select>
                </div>
            </div>
            <ol type="1" id="product-list">
                <!-- Dynamically populated product list -->
            </ol>
        </section>
    </main>

    <?php
    include_once 'includes/admin.dbh.inc.php';

    if (isset($_POST['submit_button'])) {
        // Process the form data
        $name = ucfirst($_POST['product-name']);
        $id = ucfirst($_POST['product-id']);
        $price = $_POST['product-price'];
        $description = $_POST['product-description'];
        $category = $_POST['product-category'];
        $photo = $_FILES['product-photo']['name'];
        $photoPath = "../images/products/" . $photo;
    
        if (!empty($_FILES['product-photo']['tmp_name'])) {
            move_uploaded_file($_FILES['product-photo']['tmp_name'], $photoPath);
        }
    
        // Handle featured photos
        $featuredPhotos = [];
        if (!empty($_FILES['featured-photo']['name'][0])) {
            foreach ($_FILES['featured-photo']['name'] as $index => $fileName) {
                $targetPath = "../images/featured/" . $fileName;
                move_uploaded_file($_FILES['featured-photo']['tmp_name'][$index], $targetPath);
                $featuredPhotos[] = $targetPath;
            }
        }
        $featuredPhotosSerialized = serialize($featuredPhotos);
    
        // Insert or update the product
        $checkQuery = $conn->prepare("SELECT COUNT(*) FROM gmi_db.items WHERE product_id = ?");
        $checkQuery->bind_param("s", $id);
        $checkQuery->execute();
        $checkQuery->bind_result($count);
        $checkQuery->fetch();
        $checkQuery->close();
    
        if ($count > 0) {
            $stmt = $conn->prepare("UPDATE gmi_db.items SET p_name = ?, price = ?, p_description = ?, category = ?, photo_path = ?, featured_photos = ? WHERE product_id = ?");
            $stmt->bind_param("sdsssss", $name, $price, $description, $category, $photoPath, $featuredPhotosSerialized, $id);
        } else {
            $stmt = $conn->prepare("INSERT INTO gmi_db.items (product_id, p_name, price, p_description, category, photo_path, featured_photos) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdssss", $id, $name, $price, $description, $category, $photoPath, $featuredPhotosSerialized);
        }
        $stmt->execute();
        $stmt->close();
    
        // Redirect to the same page to prevent form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }    
    

    $result = $conn->query("SELECT * FROM gmi_db.items");
    ?>


    <script  src="js/admin-dashboard.js"></script>


</body>

</html>
