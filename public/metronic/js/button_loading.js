// Element to indecate
var button = document.querySelector(".button-loading");

// Handle button click event
button.addEventListener("click", function() {
    // Activate indicator
    button.setAttribute("data-kt-indicator", "on");

    // Disable indicator after 3 seconds
    setTimeout(function() {
        button.removeAttribute("data-kt-indicator");
    }, 3000);
});
