// Toggle the mobile menu visibility (no animation)
const mobileMenuButton = document.getElementById('mobile-menu-button');
const mobileMenu = document.getElementById('mobile-menu');
const closeMobileMenuButton = document.getElementById('close-mobile-menu-button');

mobileMenuButton.addEventListener('click', () => {
    mobileMenu.classList.toggle('hidden');
});

closeMobileMenuButton.addEventListener('click', () => {
    mobileMenu.classList.add('hidden');
});

// Ensure the product menu is hidden on page load
document.addEventListener('DOMContentLoaded', () => {
    productMenuLg.classList.add('menu-hidden');
});

// Toggle the product dropdown menu visibility in desktop with click only
const productMenuButtonLg = document.getElementById('product-menu-button-lg');
const productMenuLg = document.getElementById('product-menu-lg');

// Function to show the menu
function showProductMenu() {
    productMenuLg.classList.remove('menu-hidden');
    productMenuLg.classList.add('menu-visible');
}

// Function to hide the menu with animation
function hideProductMenu() {
    productMenuLg.classList.add('menu-hiding');
    setTimeout(() => {
        productMenuLg.classList.remove('menu-visible');
        productMenuLg.classList.add('menu-hidden');
        productMenuLg.classList.remove('menu-hiding');
    }, 300); // Matches the CSS animation duration
}

// Toggle the menu visibility on click
productMenuButtonLg.addEventListener('click', () => {
    if (productMenuLg.classList.contains('menu-hidden')) {
        showProductMenu();
    } else {
        hideProductMenu();
    }
});

// Close the menu if clicked outside
document.addEventListener('click', (event) => {
    if (!productMenuButtonLg.contains(event.target) && !productMenuLg.contains(event.target)) {
        if (productMenuLg.classList.contains('menu-visible')) {
            hideProductMenu();
        }
    }
});

// Toggle the product dropdown menu visibility in mobile (no animation)
const productMenuButton = document.querySelector('button[aria-controls="disclosure-1"]');
const productSubMenu = document.getElementById('disclosure-1');

productMenuButton.addEventListener('click', () => {
    const isExpanded = productMenuButton.getAttribute('aria-expanded') === 'true';
    productMenuButton.setAttribute('aria-expanded', !isExpanded);
    productSubMenu.classList.toggle('hidden');
});


