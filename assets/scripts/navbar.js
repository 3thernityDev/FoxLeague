// navbar.js
document.addEventListener("DOMContentLoaded", function () {
    const navbarToggle = document.getElementById("navbar-toggle");
    const navbarMenu = document.querySelector(".navbar-menu");

    navbarToggle.addEventListener("click", function () {
        navbarMenu.classList.toggle("show");
    });
});
