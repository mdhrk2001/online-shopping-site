<?php
include_once 'includes/admin.dbh.inc.php';

header('Content-Type: application/json');

// Fetch query with an optional filter for category
$filterCategory = isset($_GET['category']) ? $_GET['category'] : 'all';
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Build the SQL query based on the filter
$query = "SELECT product_id, p_name, price, category, p_description, photo_path, featured_photos 
          FROM gmi_db.items WHERE 1";

// Apply category filter
if ($filterCategory !== 'all') {
    $query .= " AND category = ?";
}

// Apply search filter for product name or ID
if (!empty($searchQuery)) {
    $query .= " AND (p_name LIKE ? OR product_id LIKE ?)";
}

$stmt = $conn->prepare($query);

// Bind parameters
if ($filterCategory !== 'all' && !empty($searchQuery)) {
    $searchQuery = "%" . $searchQuery . "%"; // Prepare search query for LIKE
    $stmt->bind_param("sss", $filterCategory, $searchQuery, $searchQuery);
} elseif ($filterCategory !== 'all') {
    $stmt->bind_param("s", $filterCategory);
} elseif (!empty($searchQuery)) {
    $searchQuery = "%" . $searchQuery . "%"; // Prepare search query for LIKE
    $stmt->bind_param("ss", $searchQuery, $searchQuery);
}

$stmt->execute();
$result = $stmt->get_result();

$products = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Deserialize featured photos if they exist
        $row['featured_photos'] = unserialize($row['featured_photos']);
        $products[] = $row;
    }
}

// Return the product data as JSON
echo json_encode($products);

// Close the database connection
$conn->close();
?>
