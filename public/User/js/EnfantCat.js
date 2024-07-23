console.log('louay');
document.addEventListener("DOMContentLoaded", function () {
    var dropdownToggle = document.querySelector("#dropdown-toggle");
    var dropdownMenu = document.querySelector("#dropdown-menu");

    dropdownToggle.addEventListener("click", function () {
        dropdownMenu.style.display = (dropdownMenu.style.display === "block") ? "none" : "block";
    });

    dropdownMenu.addEventListener("mouseleave", function () {
        dropdownMenu.style.display = "none";
    });
})

