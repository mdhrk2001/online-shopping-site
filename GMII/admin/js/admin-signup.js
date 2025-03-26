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