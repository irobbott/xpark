// SIDE MENU BUTTONS (HIDDEN NAV)
// DASHBOARD
// Get the menu item container
var menuItem = document.getElementById('homeMenu');
// Add a click event listener to the menu item
menuItem.addEventListener('click', function() {
    // Redirect to the desired URL using BASE_URL
    window.location.href = baseUrl;
});

// DASHBOARD
// Get the menu item container
var menuItem = document.getElementById('dashboardMenu');
// Add a click event listener to the menu item
menuItem.addEventListener('click', function() {
    // Redirect to the desired URL using BASE_URL
    window.location.href = baseUrl + '/xpark-admin/index.php';
});

// ORDERS
// Get the menu item container
var menuItem = document.getElementById('dashboardOrders');
// Add a click event listener to the menu item
menuItem.addEventListener('click', function() {
    // Redirect to the desired URL using BASE_URL
    window.location.href = baseUrl + '/xpark-admin/orders/index.php';
});

// PRICE LISTS
// Get the menu item container
var menuItem = document.getElementById('dashboardPrices');
// Add a click event listener to the menu item
menuItem.addEventListener('click', function() {
    // Redirect to the desired URL using BASE_URL
    window.location.href = baseUrl + '/xpark-admin/prices/index.php';
});

// SETTINGS
// Get the menu item container
var menuItem = document.getElementById('dashboardSettings');
// Add a click event listener to the menu item
menuItem.addEventListener('click', function() {
    // Redirect to the desired URL using BASE_URL
    window.location.href = baseUrl + '/xpark-admin/settings/index.php';
});





// MAIN NAV
// DASHBOARD
// Get the menu item container
var menuItem = document.getElementById('homeMenu1');
// Add a click event listener to the menu item
menuItem.addEventListener('click', function() {
    // Redirect to the desired URL using BASE_URL
    window.location.href = baseUrl;
});

// DASHBOARD
// Get the menu item container
var menuItem = document.getElementById('dashboardMenu1');
// Add a click event listener to the menu item
menuItem.addEventListener('click', function() {
    // Redirect to the desired URL using BASE_URL
    window.location.href = baseUrl + '/xpark-admin/index.php';
});

// ORDERS
// Get the menu item container
var menuItem = document.getElementById('dashboardOrders1');
// Add a click event listener to the menu item
menuItem.addEventListener('click', function() {
    // Redirect to the desired URL using BASE_URL
    window.location.href = baseUrl + '/xpark-admin/orders/index.php';
});

// PRICE LISTS
// Get the menu item container
var menuItem = document.getElementById('dashboardPrices1');
// Add a click event listener to the menu item
menuItem.addEventListener('click', function() {
    // Redirect to the desired URL using BASE_URL
    window.location.href = baseUrl + '/xpark-admin/prices/index.php';
});

// SETTINGS
// Get the menu item container
var menuItem = document.getElementById('dashboardSettings1');
// Add a click event listener to the menu item
menuItem.addEventListener('click', function() {
    // Redirect to the desired URL using BASE_URL
    window.location.href = baseUrl + '/xpark-admin/settings/index.php';
});




// HIDDEN NAV
// Get the toggle button and the side bar container
var toggleButton = document.querySelector('.toggle-menu');
var sideBar = document.querySelector('.side-bar-small');

// Add click event listener to the toggle button
document.addEventListener('DOMContentLoaded', function() {
    // Get the toggle button and the side bar container
    var toggleButton = document.querySelector('.toggle-menu');
    var sideBar = document.querySelector('.side-bar-small');

    // Add click event listener to the toggle button
    toggleButton.addEventListener('click', function() {
        // Toggle the side bar visibility by changing its left position
        if (sideBar.style.left === '-250px') {
            sideBar.style.left = '0';
        } else {
            sideBar.style.left = '-250px';
        }
    });
});

// CLOSE MENU
document.addEventListener('DOMContentLoaded', function() {
    var closeMenuButtons = document.querySelectorAll('.closeMenu');
    var sideBar = document.querySelector('.side-bar-small');

    // Add click event listeners to all close menu buttons
    closeMenuButtons.forEach(function(closeMenuButton) {
        closeMenuButton.addEventListener('click', function() {
            closeSidebar();
        });
    });

    function closeSidebar() {
        // Close the side bar by setting its left position to -250px
        sideBar.style.left = '-250px';
    }
});

// ORDER APPROVAL/CANCELLATION CONFIRMATION
document.getElementById('approve-order-link').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent the default link behavior
    if (confirm('Are you sure you want to approve this order?')) {
        window.location.href = this.href; // Proceed with the link if confirmed
    }
});

document.getElementById('cancel-order-link').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent the default link behavior
    if (confirm('Are you sure you want to approve this order?')) {
        window.location.href = this.href; // Proceed with the link if confirmed
    }
});

document.getElementById('del-category').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent the default link behavior
    if (confirm('Are you sure you want to delete this category?')) {
        window.location.href = this.href; // Proceed with the link if confirmed
    }
});