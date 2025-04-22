document.addEventListener('DOMContentLoaded', function() {
    // FAQ Accordion functionality
    const faqItems = document.querySelectorAll('.faq-item');

    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        const answer = item.querySelector('.faq-answer');
        const toggle = item.querySelector('.faq-toggle');

        question.addEventListener('click', () => {
            // Close all other answers
            faqItems.forEach(otherItem => {
                if (otherItem !== item) {
                    otherItem.querySelector('.faq-answer').style.display = 'none';
                    otherItem.querySelector('.faq-toggle').innerHTML = '<i class="fa-solid fa-chevron-down"></i>';
                }
            });

            // Toggle current answer
            if (answer.style.display === 'block') {
                answer.style.display = 'none';
                toggle.innerHTML = '<i class="fa-solid fa-chevron-down"></i>';
            } else {
                answer.style.display = 'block';
                toggle.innerHTML = '<i class="fa-solid fa-chevron-up"></i>';
            }
        });
    });

    // Tab switching functionality
    const tabButtons = document.querySelectorAll('.tab-btn');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Remove active class from all buttons
            tabButtons.forEach(btn => {
                btn.classList.remove('active');
            });

            // Add active class to clicked button
            button.classList.add('active');

            // Here you would typically show/hide content based on the selected tab
            // Since this is just a UI clone, we're not implementing that functionality
        });
    });

    // Search functionality placeholder
    // const searchInput = document.querySelector('.search-container input');
    // const searchButton = document.querySelector('.search-btn');

    // searchButton.addEventListener('click', () => {
    //     if (searchInput.value.trim() !== '') {
    //         alert(`Searching for: ${searchInput.value}`);
    //         // In a real application, this would trigger a search or redirect
    //     }
    // });

    // Allow search on Enter key press
    // searchInput.addEventListener('keypress', (e) => {
    //     if (e.key === 'Enter' && searchInput.value.trim() !== '') {
    //         alert(`Searching for: ${searchInput.value}`);
    //         // In a real application, this would trigger a search or redirect
    //     }
    // });

    const menuMobile = document.querySelector('.menu-mobile-content');
    const menuMobileClose = document.querySelector('.menu-mobile-close');
    const menuMobileOpen = document.querySelector('.menu-mobile-open');

    menuMobileClose.addEventListener('click', () => {
        menuMobile.classList.remove('active');
    });

    menuMobileOpen.addEventListener('click', () => {
        menuMobile.classList.add('active');
    });
});
