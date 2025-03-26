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