let currentEditIndex = null; // Variable to track the category being edited

// Function to display the list of categories
function displayCategories() {
    const categoryList = document.getElementById("category-list");

    // Clear the category list while preserving hardcoded existing categories
    const existingCategoriesHTML = `
        <li>Electronics</li>
        <li>Fashion</li>
        <li>Home</li>
        <li>Health</li>
    `;
    categoryList.innerHTML = existingCategoriesHTML;

    // Retrieve categories from localStorage
    const categories = JSON.parse(localStorage.getItem("categories")) || [];

    // Render each new category in the list
    categories.forEach((category, index) => {
        const categoryItem = document.createElement("li");
        categoryItem.innerHTML = `
            <span>${category}</span>
            <div>
                <button class="edit-btn" onclick="startEditingCategory(${index})">Edit</button>
                <button class="delete-btn" onclick="deleteCategory(${index})">Delete</button>
            </div>
        `;
        categoryList.appendChild(categoryItem);
    });
}

// Function to notify other modules of category updates
function updateCategoryDropdowns() {
    const event = new Event("categoriesUpdated");
    document.dispatchEvent(event);
}

// Function to start editing a category
function startEditingCategory(index) {
    const categories = JSON.parse(localStorage.getItem("categories")) || [];
    const categoryName = categories[index];

    // Set the input field value to the selected category name
    document.getElementById("category-name").value = categoryName;

    // Highlight the category being edited
    currentEditIndex = index;

    // Change the button text to 'Update Category'
    const submitButton = document.querySelector("#category-form button[type='submit']");
    submitButton.textContent = "Update Category";
}


// Function to add or update a category
document.getElementById("category-form").addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent form submission

    const categoryName = document.getElementById("category-name").value.trim().toLowerCase(); // Convert to lowercase for case-insensitive comparison
    const formattedName = categoryName.charAt(0).toUpperCase() + categoryName.slice(1); // Capitalize first letter

    // Predefined categories
    const predefinedCategories = ["Electronics", "Fashion", "Home", "Health"].map(cat => cat.toLowerCase());

    // Retrieve categories from localStorage
    let categories = JSON.parse(localStorage.getItem("categories")) || [];
    const allCategories = predefinedCategories.concat(categories.map(cat => cat.toLowerCase()));

    // Check if the category already exists in predefined or custom categories
    if (allCategories.includes(categoryName)) {
        alert("Category already exists!");
        return;
    }

    if (currentEditIndex !== null) {
        // Update the category if in edit mode
        categories[currentEditIndex] = formattedName;
        alert("Category updated successfully!");
        currentEditIndex = null;
    } else {
        // Add new category if not in edit mode
        categories.push(formattedName);
        alert("Category added successfully!");
    }

    // Save the updated categories in localStorage
    localStorage.setItem("categories", JSON.stringify(categories));

    // Reset the form and update the category list
    document.getElementById("category-form").reset();
    const submitButton = document.querySelector("#add-category-form button[type='submit']");
    submitButton.textContent = "Add Category"; // Reset button text
    displayCategories();
    updateCategoryDropdowns(); // Notify dropdowns to update
});


// Function to delete a category
function deleteCategory(index) {
    const categories = JSON.parse(localStorage.getItem("categories")) || [];
    categories.splice(index, 1); // Remove the category at the given index
    localStorage.setItem("categories", JSON.stringify(categories)); // Update localStorage
    displayCategories(); // Refresh the category list
    alert("Category deleted successfully!");
    updateCategoryDropdowns(); // Notify dropdowns to update
}

// Load and display categories on page load
document.addEventListener("DOMContentLoaded", displayCategories);