// Authentication Pages JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Form validation for registration
    const registerForm = document.querySelector('form[action="register.php"]');
    
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            let isValid = true;
            let errorMessages = [];
            
            // Validate username
            if (username === '') {
                isValid = false;
                errorMessages.push('Username is required');
            } else if (username.length < 3 || username.length > 20) {
                isValid = false;
                errorMessages.push('Username must be between 3 and 20 characters');
            }
            
            // Validate email
            if (email === '') {
                isValid = false;
                errorMessages.push('Email is required');
            } else if (!isValidEmail(email)) {
                isValid = false;
                errorMessages.push('Please enter a valid email address');
            }
            
            // Validate password
            if (password === '') {
                isValid = false;
                errorMessages.push('Password is required');
            } else if (password.length < 6) {
                isValid = false;
                errorMessages.push('Password must be at least 6 characters');
            }
            
            // Validate password confirmation
            if (password !== confirmPassword) {
                isValid = false;
                errorMessages.push('Passwords do not match');
            }
            
            if (!isValid) {
                e.preventDefault();
                alert(errorMessages.join('\n'));
            }
        });
    }
    
    // Form validation for login
    const loginForm = document.querySelector('form[action="login.php"]');
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const identifier = document.getElementById('identifier').value.trim();
            const password = document.getElementById('password').value;
            
            let isValid = true;
            let errorMessages = [];
            
            // Validate identifier
            if (identifier === '') {
                isValid = false;
                errorMessages.push('Username or Email is required');
            }
            
            // Validate password
            if (password === '') {
                isValid = false;
                errorMessages.push('Password is required');
            }
            
            if (!isValid) {
                e.preventDefault();
                alert(errorMessages.join('\n'));
            }
        });
    }
    
    // Password strength meter
    const passwordInput = document.getElementById('password');
    
    if (passwordInput && document.querySelector('form[action="register.php"]')) {
        // Create strength meter element
        const strengthMeter = document.createElement('div');
        strengthMeter.className = 'password-strength';
        strengthMeter.innerHTML = `
            <div class="strength-meter">
                <div class="strength-meter-fill"></div>
            </div>
            <div class="strength-text"></div>
        `;
        
        // Insert after password field
        passwordInput.parentNode.appendChild(strengthMeter);
        
        // Get elements
        const strengthMeterFill = strengthMeter.querySelector('.strength-meter-fill');
        const strengthText = strengthMeter.querySelector('.strength-text');
        
        // Update strength meter on input
        passwordInput.addEventListener('input', function() {
            const val = this.value;
            let strength = 0;
            let message = '';
            
            // Calculate password strength
            if (val.length >= 6) strength += 1;
            if (val.length >= 10) strength += 1;
            if (/[A-Z]/.test(val)) strength += 1;
            if (/[0-9]/.test(val)) strength += 1;
            if (/[^A-Za-z0-9]/.test(val)) strength += 1;
            
            // Update UI based on strength
            switch (strength) {
                case 0:
                    strengthMeterFill.style.width = '0%';
                    strengthMeterFill.style.backgroundColor = '#ddd';
                    message = '';
                    break;
                case 1:
                    strengthMeterFill.style.width = '20%';
                    strengthMeterFill.style.backgroundColor = '#ff3860';
                    message = 'Very weak';
                    break;
                case 2:
                    strengthMeterFill.style.width = '40%';
                    strengthMeterFill.style.backgroundColor = '#ff3860';
                    message = 'Weak';
                    break;
                case 3:
                    strengthMeterFill.style.width = '60%';
                    strengthMeterFill.style.backgroundColor = '#ffdd57';
                    message = 'Medium';
                    break;
                case 4:
                    strengthMeterFill.style.width = '80%';
                    strengthMeterFill.style.backgroundColor = '#48c78e';
                    message = 'Strong';
                    break;
                case 5:
                    strengthMeterFill.style.width = '100%';
                    strengthMeterFill.style.backgroundColor = '#48c78e';
                    message = 'Very strong';
                    break;
            }
            
            strengthText.textContent = message;
        });
    }
    
    // Email validation function
    function isValidEmail(email) {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }
    
    // Add CSS for password strength meter
    const style = document.createElement('style');
    style.textContent = `
        .password-strength {
            margin-top: 8px;
        }
        
        .strength-meter {
            height: 4px;
            background-color: #ddd;
            border-radius: 2px;
            margin-bottom: 6px;
        }
        
        .strength-meter-fill {
            height: 100%;
            border-radius: 2px;
            transition: width 0.3s ease-in-out, background-color 0.3s ease-in-out;
            width: 0;
        }
        
        .strength-text {
            font-size: 12px;
            color: var(--color-text-lighter);
        }
    `;
    document.head.appendChild(style);
});