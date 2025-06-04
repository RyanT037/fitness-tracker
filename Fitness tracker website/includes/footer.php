<footer class="main-footer">
    <div class="footer-container">
        <div class="footer-top">
            <div class="footer-logo">
                <span class="logo-text">FitTrack</span>
                <p>Your personal fitness companion</p>
            </div>
            
            <div class="footer-links">
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="bmi-calculator.php">BMI Calculator</a></li>
                        <li><a href="exercises.php">Exercises</a></li>
                        <li><a href="forum.php">Community</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Resources</h3>
                    <ul>
                        <li><a href="fitness-tips.php">Fitness Tips</a></li>
                        <li><a href="nutrition.php">Nutrition Guide</a></li>
                        <li><a href="faq.php">FAQs</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Account</h3>
                    <ul>
                        <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="dashboard.php">Dashboard</a></li>
                        <li><a href="profile.php">Profile</a></li>
                        <li><a href="logout.php">Logout</a></li>
                        <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            
            <div class="footer-newsletter">
                <h3>Stay Updated</h3>
                <p>Subscribe to our newsletter for fitness tips and updates</p>
                <form class="newsletter-form">
                    <input type="email" placeholder="Your email address">
                    <button type="submit" class="btn btn-primary">Subscribe</button>
                </form>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div class="social-links">
                <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
            </div>
            
            <div class="copyright">
                <p>&copy; <?php echo date('Y'); ?> FitTrack. All rights reserved.</p>
            </div>
            
            <div class="footer-legal">
                <a href="privacy.php">Privacy Policy</a>
                <a href="terms.php">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>