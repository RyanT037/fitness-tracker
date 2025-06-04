// Dashboard Page JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Tab switching functionality
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    if (tabButtons.length > 0 && tabContents.length > 0) {
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                tabButtons.forEach(btn => btn.classList.remove('active'));
                
                // Add active class to clicked button
                this.classList.add('active');
                
                // Hide all tab contents
                tabContents.forEach(content => content.classList.add('hidden'));
                
                // Show selected tab content
                const tabId = this.getAttribute('data-tab');
                document.getElementById(`${tabId}-tab`).classList.remove('hidden');
            });
        });
    }
    
    // Close welcome message
    const closeWelcome = document.querySelector('.close-welcome');
    const welcomeMessage = document.querySelector('.welcome-message');
    
    if (closeWelcome && welcomeMessage) {
        closeWelcome.addEventListener('click', function() {
            welcomeMessage.style.opacity = '0';
            
            setTimeout(() => {
                welcomeMessage.style.display = 'none';
            }, 300);
            
            // Save preference in localStorage
            localStorage.setItem('welcomeMessageClosed', 'true');
        });
        
        // Check if welcome message was closed before
        if (localStorage.getItem('welcomeMessageClosed') === 'true') {
            welcomeMessage.style.display = 'none';
        }
    }
    
    // Add animations to stat items
    const statItems = document.querySelectorAll('.stat-item');
    
    if (statItems.length > 0) {
        statItems.forEach((item, index) => {
            setTimeout(() => {
                item.style.opacity = '0';
                item.style.transform = 'translateY(20px)';
                item.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                
                setTimeout(() => {
                    item.style.opacity = '1';
                    item.style.transform = 'translateY(0)';
                }, 100);
            }, index * 100);
        });
    }
    
    // Recommended exercises hover effect
    const recommendedItems = document.querySelectorAll('.recommended-item');
    
    if (recommendedItems.length > 0) {
        recommendedItems.forEach(item => {
            item.addEventListener('mouseenter', function() {
                const icon = this.querySelector('.recommended-icon');
                
                icon.style.transition = 'transform 0.3s ease';
                icon.style.transform = 'scale(1.2)';
            });
            
            item.addEventListener('mouseleave', function() {
                const icon = this.querySelector('.recommended-icon');
                
                icon.style.transform = 'scale(1)';
            });
        });
    }
});