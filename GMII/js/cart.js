function removeFromCart(cartId) {
    fetch(`remove-from-cart.php?id=${cartId}`, { method: 'GET' })
        .then(response => response.text())
        .then(data => {
            alert(data);
            location.reload(); // Reload the page to reflect the updated cart
        })
        .catch(error => console.error('Error:', error));
}

function clearCart() {
    fetch('clear-cart.php', { method: 'GET' })
        .then(response => response.text())
        .then(data => {
            alert(data);
            location.reload(); // Reload the page to reflect the updated cart
        })
        .catch(error => console.error('Error:', error));
}