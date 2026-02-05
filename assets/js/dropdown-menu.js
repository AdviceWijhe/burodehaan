document.addEventListener('DOMContentLoaded', function() {
    // Get all menu items with dropdowns
    const dropdownItems = document.querySelectorAll('[data-has-dropdown="true"]');
    
    
    dropdownItems.forEach(function(item, index) {
        // Find the dropdown that belongs to this menu item
        const dropdownId = item.getAttribute('data-dropdown-id');
        const dropdown = document.querySelector(`.dropdown-menu-${dropdownId.replace('menu-item-', '')}`);
        
        
        if (!dropdown) {
            return;
        }
        
        let timeout;
        
        // Show dropdown on mouse enter
        item.addEventListener('mouseenter', function() {
            clearTimeout(timeout);
            
            // Hide all other dropdowns first
            hideAllDropdowns();
            
            // Show this dropdown
            showDropdown(dropdown);
        });
        
        // Hide dropdown on mouse leave
        item.addEventListener('mouseleave', function() {
            timeout = setTimeout(function() {
                hideDropdown(dropdown);
            }, 100); // Small delay to prevent flickering
        });
        
        // Keep dropdown open when hovering over it
        dropdown.addEventListener('mouseenter', function() {
            clearTimeout(timeout);
        });
        
        // Hide dropdown when leaving it
        dropdown.addEventListener('mouseleave', function() {
            timeout = setTimeout(function() {
                hideDropdown(dropdown);
            }, 100);
        });
    });
    
    // Function to show dropdown
    function showDropdown(dropdown) {
        dropdown.style.opacity = '1';
        dropdown.style.visibility = 'visible';
        dropdown.style.transform = 'translateY(0)';
    }
    
    // Function to hide dropdown
    function hideDropdown(dropdown) {
        dropdown.style.opacity = '0';
        dropdown.style.visibility = 'hidden';
        dropdown.style.transform = 'translateY(1rem)';
    }
    
    // Function to hide all dropdowns
    function hideAllDropdowns() {
        const allDropdowns = document.querySelectorAll('.dropdown-menu');
        allDropdowns.forEach(function(dropdown) {
            hideDropdown(dropdown);
        });
    }
    
    // Hide dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.has-dropdown') && !event.target.closest('.dropdown-menu')) {
            hideAllDropdowns();
        }
    });
});
