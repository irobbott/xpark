///////////////////////////////////////////////
///////////////////////////////////////////////
// HIDDEN NAV
document.addEventListener('DOMContentLoaded', function() {
    var toggleButton = document.querySelector('.toggle-menu');
    var sideBar = document.querySelector('.mnav');
    var closeMenuButtons = document.querySelectorAll('.closeMenu');

    // Function to open the sidebar
    function openSidebar() {
        sideBar.style.top = '0';
    }

    // Function to close the sidebar
    function closeSidebar() {
        sideBar.style.top = '-1500px';
    }

    // Toggle button event listener
    toggleButton.addEventListener('click', function() {
        if (sideBar.style.top === '0px') {
            closeSidebar();
        } else {
            openSidebar();
        }
    });

    // Close button event listeners
    closeMenuButtons.forEach(function(closeMenuButton) {
        closeMenuButton.addEventListener('click', closeSidebar);
    });
});

// HIDDEN NAV FOR SCROLL MENU
document.addEventListener('DOMContentLoaded', function() {
    var toggleButton = document.querySelector('.toggle-menu-2');
    var sideBar = document.querySelector('.mnav');
    var closeMenuButtons = document.querySelectorAll('.closeMenu');

    // Function to open the sidebar
    function openSidebar() {
        sideBar.style.top = '0';
    }

    // Function to close the sidebar
    function closeSidebar() {
        sideBar.style.top = '-1500px';
    }

    // Toggle button event listener
    toggleButton.addEventListener('click', function() {
        if (sideBar.style.top === '0px') {
            closeSidebar();
        } else {
            openSidebar();
        }
    });

    // Close button event listeners
    closeMenuButtons.forEach(function(closeMenuButton) {
        closeMenuButton.addEventListener('click', closeSidebar);
    });
});

// DISPLAY SCROLL NAVBAR
document.addEventListener('DOMContentLoaded', function() {
    var navbar = document.querySelector('.xnav1');
    var scrollTrigger = 200; // The scroll position to show the navbar

    window.addEventListener('scroll', function() {
        if (window.scrollY >= scrollTrigger) {
            navbar.classList.remove('hidden');
            navbar.classList.add('visible');
        } else {
            navbar.classList.remove('visible');
            navbar.classList.add('hidden');
        }
    });
});

// SCROLLING AND URL
// Smooth scroll function
function scrollToElement(element) {
    window.scrollTo({
      behavior: 'smooth',
      top: element.offsetTop
    });
  }

// Event listener for anchor tag clicks
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      scrollToElement(target);
    });
});