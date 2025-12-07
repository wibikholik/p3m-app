/**
 * Sidebar Menu Toggle Script
 * Menangani expand/collapse menu di sidebar
 */

function toggleMenu(element, menuId) {
  const menu = document.getElementById(menuId);

  if (menu) {
    const isVisible = menu.style.display !== 'none';

    // Sembunyikan semua menu lain
    const allMenus = document.querySelectorAll('.collapse-menu');
    allMenus.forEach(m => {
      m.style.display = 'none';
      const toggleLink = m.parentElement.querySelector('.menu-toggle');
      if (toggleLink) {
        toggleLink.classList.remove('active');
      }
    });

    // Toggle menu saat ini
    if (!isVisible) {
      menu.style.display = 'block';
      element.classList.add('active');
    } else {
      menu.style.display = 'none';
      element.classList.remove('active');
    }
  }
}

// Initialize on document ready
document.addEventListener('DOMContentLoaded', function() {
  // Tambahkan event listener untuk semua menu toggle
  const menuToggles = document.querySelectorAll('.menu-toggle');

  menuToggles.forEach(toggle => {
    toggle.addEventListener('mouseenter', function() {
      if (this.classList.contains('active')) {
        this.style.backgroundColor = 'rgba(0, 123, 255, 0.15)';
      }
    });

    toggle.addEventListener('mouseleave', function() {
      if (this.classList.contains('active')) {
        this.style.backgroundColor = 'rgba(0, 123, 255, 0.1)';
      }
    });
  });
});
