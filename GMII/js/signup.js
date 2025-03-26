function updateCharacterCount(input) {
    // Add '@' at the beginning if it's not there
    if (!input.value.startsWith('@') && input.value.length > 0) {
        input.value = '@' + input.value.replace(/@/g, ''); // Ensure only one '@'
    }

    const currentLength = input.value.length;

    // Update the character count display
    const characterCountElement = document.getElementById('characterCount');
    characterCountElement.textContent = `[${currentLength}/10]`;
}


function checkUserHandleAvailability(input) {
    const userHandle = input.value.trim();
    const statusDiv = document.getElementById('userHandleStatus');

    // Regex for valid user handles: only letters, numbers, underscores, and must start with '@'
    const validHandleRegex = /^@[A-Za-z0-9_]*$/;

    // Allow empty input or '@' only
    if (userHandle === "" || userHandle === "@") {
        statusDiv.textContent = ""; // Clear the status
        return;
    }

    // Check for invalid characters
    if (!validHandleRegex.test(userHandle)) {
        statusDiv.textContent = "❌ Handle contains invalid characters. Only letters, numbers, and underscores are allowed.";
        statusDiv.style.color = "red";
        return;
    }

    // Check if the handle length is sufficient
    if (userHandle.length < 5) {
        statusDiv.textContent = "⚠️ Handle must be at least 5 characters.";
        statusDiv.style.color = "red";
        return;
    }

    // Make an AJAX request only for valid handles
    if (userHandle.length > 1) { // Avoid querying for empty or '@'
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "includes/check_userhandle.inc.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                statusDiv.textContent = xhr.responseText.trim();
                statusDiv.style.color = xhr.responseText.includes("available") ? "green" : "red";
            }
        };

        xhr.send("userHandle=" + encodeURIComponent(userHandle));
    } else {
        statusDiv.textContent = ""; // Clear the status if input is too short
    }
}




function togglePasswordVisibility(passwordFieldId, toggleIcon) {
    const passwordField = document.getElementById(passwordFieldId);
    if (passwordField.type === "password") {
        passwordField.type = "text";
        toggleIcon.textContent = "😵";
    } else {
        passwordField.type = "password";
        toggleIcon.textContent = "👁️";
    }
}


const errorMessage = document.getElementById("errorMessage");

setTimeout(() => {
errorMessage.style.visibility = "hidden";
}, 5000);


function goBack() {
    window.history.back();
}


function togglePopup(event) {
    const popup = document.getElementById('popup');
    if (popup.style.display === 'none' || popup.style.display === '') {
    popup.style.display = 'block';
    } else {
    popup.style.display = 'none';
    }
}

// Optional: Close the popup if the user clicks outside of it
document.addEventListener('click', function(event) {
    const popup = document.getElementById('popup');
    if (!event.target.closest('#popup') && !event.target.closest('a')) {
    popup.style.display = 'none';
    }
});