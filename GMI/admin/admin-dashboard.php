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

    <script>
        const productList = document.getElementById("product-list");

        function loadProducts() {
            const filterCategory = document.getElementById("filter-category").value;
            const searchQuery = document.getElementById("search-box").value;

            // Create URL with category and search filters
            let url = `fetch-products.php?category=${filterCategory}&search=${searchQuery}`;

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.json();
                })
                .then(data => {
                    productList.innerHTML = "";
                    if (data.length === 0) {
                        productList.innerHTML = "<li>No products found.</li>";
                    } else {
                        data.forEach(product => {
                            const productItem = document.createElement("li");
                            productItem.innerHTML = `
                                <strong>${product.product_id}</strong> - ${product.p_name} - LKR.${product.price}
                                <button class="delete-btn" onclick="deleteProduct('${product.product_id}')">Delete</button>
                                <button class="edit-btn" onclick="editProduct('${product.product_id}')">Edit</button>
                            `;
                            productList.appendChild(productItem);
                        });
                    }
                })
                .catch(error => {
                    console.error("Error loading products:", error);
                });
        }

        function deleteProduct(productId) {
            fetch("delete-product.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ product_id: productId })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Failed to delete product.");
                    }
                    return response.text();
                })
                .then(data => {
                    loadProducts(); // Reload the product list
                })
                .catch(error => {
                    console.error("Error deleting product:", error);
                });
        }


        // Function to edit a product
        function editProduct(productId) {
            // Fetch product details using AJAX
            fetch(`fetch-product-details.php?product_id=${productId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Failed to fetch product details.");
                    }
                    return response.json();
                })
                .then(product => {
                    // Populate the form fields with product details
                    document.getElementById("product-name").value = product.p_name;
                    document.getElementById("product-id").value = product.product_id;
                    document.getElementById("product-price").value = product.price;
                    document.getElementById("product-description").value = product.p_description;
                    document.getElementById("product-category").value = product.category;

                    // Disable the Product ID field to prevent changes
                    document.getElementById("product-id").readOnly = true;

                    // Change the button text to "Save Changes"
                    const submitButton = document.querySelector("#add-product-form button[type='submit']");
                    submitButton.textContent = "Save Changes";
                    submitButton.classList.add("save-changes-btn");

                    // Set a flag to indicate that the form is in "edit" mode
                    document.getElementById("add-product-form").dataset.editMode = "true";
                    document.getElementById("add-product-form").dataset.editProductId = productId;

                    // Focus on the first form field for better UX
                    document.getElementById("product-name").focus();
                })
                .catch(error => {
                    console.error("Error fetching product details:", error);
                });
        }

        // Event listener for form submission
        document.getElementById("add-product-form").addEventListener("submit", function (e) {

            // Check if the form is in "edit" mode
            if (this.dataset.editMode === "true") {
                // Prepare the updated product data
                const productId = this.dataset.editProductId;
                const formData = new FormData(this);
                formData.append("product_id", productId);

                // Send updated data to the server via AJAX
                fetch("update-product.php", {
                    method: "POST",
                    body: formData
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error("Failed to update product.");
                        }
                        return response.text();
                    })
                    .then(data => {
                        console.log("Product updated successfully:", data);
                        // Reload the product list and reset the form
                        loadProducts();
                        resetForm();
                    })
                    .catch(error => {
                        console.error("Error updating product:", error);
                    });
            } else {
                // Handle the normal add product functionality here
                this.submit();
            }
        });

        // Function to reset the form
        function resetForm() {
            const form = document.getElementById("add-product-form");
            form.reset();
            document.getElementById("product-id").disabled = false;

            // Reset the button text
            const submitButton = form.querySelector("button[type='submit']");
            submitButton.textContent = "Add Product";
            submitButton.classList.remove("save-changes-btn");

            // Reset edit mode
            form.dataset.editMode = "false";
            form.dataset.editProductId = "";
        }



        // Event listeners for category filter and search input
        document.getElementById("filter-category").addEventListener("change", loadProducts);
        document.getElementById("search-box").addEventListener("input", loadProducts);

        document.addEventListener("DOMContentLoaded", loadProducts);
    </script>
</body>

</html>
