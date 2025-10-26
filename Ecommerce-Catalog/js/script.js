document.addEventListener('DOMContentLoaded', function() {
    // Add loading animation
    const cards = document.querySelectorAll('.product-card, .category-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Add smooth scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // Add search suggestions (basic implementation)
    const searchInput = document.querySelector('input[name="q"]');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            // You can implement AJAX search suggestions here
            console.log('Searching for:', this.value);
        });
    }

    // Add image lazy loading
    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });

    images.forEach(img => imageObserver.observe(img));
});

// Add to cart functionality (placeholder)
function addToCart(productId) {
    alert('Product added to cart! (Feature to be implemented)');
    // Here you can implement actual cart functionality
}

// Quick view functionality
function quickView(productId) {
    // Implement modal quick view
    console.log('Quick view for product:', productId);
}
