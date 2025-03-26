function searchItems() {
    const searchInput = document.getElementById('search-input').value.toLowerCase();
    const productCards = document.querySelectorAll('.product-card');

    productCards.forEach(card => {
        const productName = card.querySelector('h2').textContent.toLowerCase();
        if (productName.includes(searchInput)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });

    document.getElementById('search-input').value = '';
}

function filterByCategory() {
    const selectedCategory = document.getElementById('category-dropdown').value;
    const productCards = document.querySelectorAll('.product-card');

    productCards.forEach(card => {
        if (selectedCategory === 'all' || card.dataset.category === selectedCategory) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

function addToCart(name, price, imgSrc) {
    fetch('add-to-cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ name, price, imgSrc })
    })
    .then(response => response.text())
    .then(data => {
        if (data.includes("Error: User is not logged in")) {
            alert("Please log in to add items to the cart.");
            window.location.href = "login.php"; // Redirect to login page
        } else {
            alert(data); // Display success or error message
        }
    })
    .catch(error => console.error('Error:', error));
}