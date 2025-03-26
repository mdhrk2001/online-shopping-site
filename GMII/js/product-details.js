let currentSlideIndex = 0; // Current slide index

document.addEventListener("DOMContentLoaded", function () {
    // Display the first slide immediately after the page loads
    showSlide(currentSlideIndex);

    // Auto slideshow starts after the first display
    setInterval(() => {
        currentSlideIndex++;
        showSlide(currentSlideIndex);
    }, 4000); // Change slide every 5 seconds
});

function showSlide(index) {
    const slides = document.querySelectorAll(".slide");
    if (slides.length > 0) {
        slides.forEach(slide => (slide.style.display = "none")); // Hide all slides
        if (index >= slides.length) currentSlideIndex = 0; // Loop back to the first slide
        if (index < 0) currentSlideIndex = slides.length - 1; // Loop back to the last slide
        slides[currentSlideIndex].style.display = "block"; // Show the current slide
    }
}

function nextSlide() {
    currentSlideIndex++;
    showSlide(currentSlideIndex);
}

function prevSlide() {
    currentSlideIndex--;
    showSlide(currentSlideIndex);
}