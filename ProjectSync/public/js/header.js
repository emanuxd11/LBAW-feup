document.addEventListener("DOMContentLoaded", function () {
    var profileDropdownToggle = document.getElementById("profile-dropdown-toggle");
    var profileDropdown = document.getElementById("profile-dropdown");

    profileDropdownToggle.addEventListener("click", function (event) {
        event.stopPropagation(); // Prevent the window click event from closing the dropdown
        profileDropdown.style.display = (profileDropdown.style.display === "block") ? "none" : "block";
    });

    // Close the dropdown when clicking outside of it
    window.addEventListener("click", function () {
        if (profileDropdown.style.display === "block") {
            profileDropdown.style.display = "none";
        }
    });

    // Prevent the dropdown from closing when clicking inside it
    profileDropdown.addEventListener("click", function (event) {
        event.stopPropagation();
    });
});
