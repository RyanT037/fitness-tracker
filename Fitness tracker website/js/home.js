// Home Page JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Testimonial slider
    const testimonialSlider = document.querySelector('.testimonial-slider');
    const testimonialCards = document.querySelectorAll('.testimonial-card');
    
    if (testimonialSlider && testimonialCards.length > 1) {
        let currentSlide = 0;
        const slideCount = testimonialCards.length;
        let slideInterval;
        
        // Initialize slider
        function initSlider() {
            // Clone cards for infinite loop
            testimonialCards.forEach(card => {
                const clone = card.cloneNode(true);
                testimonialSlider.appendChild(clone);
            });
            
            // Set initial position
            updateSliderPosition();
            
            // Start auto sliding
            startSlider();
        }
        
        // Update slider position
        function updateSliderPosition() {
            const slideWidth = testimonialCards[0].offsetWidth + parseInt(getComputedStyle(testimonialCards[0]).marginRight);
            testimonialSlider.style.transition = 'transform 0.5s ease-in-out';
            testimonialSlider.style.transform = `translateX(-${currentSlide * slideWidth}px)`;
            
            // Reset to first slide when reaching end
            if (currentSlide >= slideCount) {
                setTimeout(() => {
                    testimonialSlider.style.transition = 'none';
                    currentSlide = 0;
                    testimonialSlider.style.transform = `translateX(0)`;
                }, 500);
            }
        }
        
        // Start auto sliding
        function startSlider() {
            slideInterval = setInterval(() => {
                currentSlide++;
                updateSliderPosition();
            }, 5000);
        }
        
        // Stop auto sliding
        function stopSlider() {
            clearInterval(slideInterval);
        }
        
        // Initialize on desktop only
        if (window.innerWidth >= 768) {
            // Add necessary styles
            testimonialSlider.style.display = 'flex';
            testimonialSlider.style.width = 'fit-content';
            
            testimonialCards.forEach(card => {
                card.style.flex = '0 0 auto';
                card.style.marginRight = '20px';
                card.style.width = `${Math.min(400, window.innerWidth - 40)}px`;
            });
            
            initSlider();
            
            // Pause slider on hover
            testimonialSlider.addEventListener('mouseenter', stopSlider);
            testimonialSlider.addEventListener('mouseleave', startSlider);
        }
        
        // Reinitialize on window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                stopSlider();
                testimonialSlider.style.transition = 'none';
                testimonialSlider.style.transform = 'translateX(0)';
                currentSlide = 0;
                
                // Reset styles
                testimonialSlider.style.display = 'flex';
                testimonialSlider.style.width = 'fit-content';
                
                testimonialCards.forEach(card => {
                    card.style.flex = '0 0 auto';
                    card.style.marginRight = '20px';
                    card.style.width = `${Math.min(400, window.innerWidth - 40)}px`;
                });
                
                startSlider();
            } else {
                stopSlider();
                testimonialSlider.style.transform = '';
                testimonialSlider.style.display = 'grid';
                testimonialSlider.style.width = '';
                
                testimonialCards.forEach(card => {
                    card.style.flex = '';
                    card.style.marginRight = '';
                    card.style.width = '';
                });
            }
        });
    }
    
    // Add animation to hero section
    const heroContent = document.querySelector('.hero-content');
    const heroImage = document.querySelector('.hero-image');
    
    if (heroContent) {
        heroContent.style.opacity = '0';
        heroContent.style.transform = 'translateY(20px)';
        heroContent.style.transition = 'opacity 0.8s ease-in-out, transform 0.8s ease-in-out';
        
        setTimeout(() => {
            heroContent.style.opacity = '1';
            heroContent.style.transform = 'translateY(0)';
        }, 300);
    }
    
    if (heroImage) {
        heroImage.style.opacity = '0';
        heroImage.style.transform = 'translateX(20px)';
        heroImage.style.transition = 'opacity 0.8s ease-in-out, transform 0.8s ease-in-out';
        
        setTimeout(() => {
            heroImage.style.opacity = '1';
            heroImage.style.transform = 'translateX(0)';
        }, 600);
    }
    
    // Animate feature cards on scroll
    const featureCards = document.querySelectorAll('.feature-card');
    
    if (featureCards.length > 0) {
        // Set initial state
        featureCards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'opacity 0.6s ease-in-out, transform 0.6s ease-in-out';
        });
        
        // Check if element is in viewport
        function isInViewport(element) {
            const rect = element.getBoundingClientRect();
            return (
                rect.top <= (window.innerHeight || document.documentElement.clientHeight) * 0.8
            );
        }
        
        // Animate elements in viewport
        function animateOnScroll() {
            featureCards.forEach(card => {
                if (isInViewport(card) && card.style.opacity === '0') {
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 100);
                }
            });
        }
        
        // Run once on load
        animateOnScroll();
        
        // Run on scroll
        window.addEventListener('scroll', animateOnScroll);
    }
});