// BMI Calculator JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Handle height unit change
    const heightInput = document.getElementById('height');
    const heightUnitSelect = document.getElementById('height-unit');
    
    if (heightInput && heightUnitSelect) {
        heightUnitSelect.addEventListener('change', function() {
            const currentValue = parseFloat(heightInput.value) || 0;
            const currentUnit = this.value;
            
            // Set appropriate min, max, and step based on unit
            if (currentUnit === 'cm') {
                heightInput.setAttribute('min', '50');
                heightInput.setAttribute('max', '300');
                heightInput.setAttribute('step', '1');
                
                // Convert from meters to cm if needed
                if (currentValue > 0 && currentValue < 10) {
                    heightInput.value = (currentValue * 100).toFixed(0);
                }
            } else {
                heightInput.setAttribute('min', '0.5');
                heightInput.setAttribute('max', '3');
                heightInput.setAttribute('step', '0.01');
                
                // Convert from cm to meters if needed
                if (currentValue > 10) {
                    heightInput.value = (currentValue / 100).toFixed(2);
                }
            }
        });
    }
    
    // Form validation
    const bmiForm = document.querySelector('.bmi-form');
    
    if (bmiForm) {
        bmiForm.addEventListener('submit', function(e) {
            const weight = parseFloat(document.getElementById('weight').value);
            const height = parseFloat(document.getElementById('height').value);
            const heightUnit = document.getElementById('height-unit').value;
            
            let isValid = true;
            let heightInMeters = height;
            
            // Convert height to meters if in cm
            if (heightUnit === 'cm') {
                heightInMeters = height / 100;
            }
            
            // Validate weight
            if (isNaN(weight) || weight <= 0 || weight > 300) {
                isValid = false;
                alert('Please enter a valid weight between 1 and 300 kg.');
            }
            
            // Validate height
            if (isNaN(heightInMeters) || heightInMeters <= 0 || heightInMeters > 3) {
                isValid = false;
                alert('Please enter a valid height.');
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
    
    // BMI result visualization
    const bmiValue = document.querySelector('.bmi-value');
    
    if (bmiValue) {
        const bmi = parseFloat(bmiValue.textContent);
        const bmiScore = bmiValue.closest('.bmi-score');
        
        // Add animation to BMI value
        bmiValue.style.opacity = '0';
        
        setTimeout(() => {
            bmiValue.style.transition = 'opacity 0.5s ease-in-out';
            bmiValue.style.opacity = '1';
        }, 500);
        
        // Add pulse animation to BMI category
        if (bmiScore) {
            bmiScore.classList.add('pulse-animation');
        }
    }
});