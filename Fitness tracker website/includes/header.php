<header class="main-header">
    <div class="header-container">
        <div class="logo">
            <a href="index.php">
                <span class="logo-text">FitTrack</span>
            </a>
        </div>
        
        <nav class="main-nav">
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="bmi-calculator.php">BMI Calculator</a></li>
                <li><a href="exercises.php">Exercises</a></li>
                <li><a href="forum.php">Community</a></li>
                <li><a href="dietary-meal-plans.php">Meal Plans</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="dashboard.php">Dashboard</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        
        <div class="auth-nav">
            <?php if (isset($_SESSION['user_id'])): ?>
            <div class="user-menu">
                <button class="user-menu-btn">
                    <span><?php echo htmlspecialchars(isset($_SESSION['username']) ? $_SESSION['username'] : ''); ?></span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
                    <li><a href="workout-history.php"><i class="fas fa-history"></i> Workout History</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
            <?php else: ?>
            <div class="auth-buttons">
                <a href="login.php" class="btn btn-outline">Log In</a>
                <a href="register.php" class="btn btn-primary">Sign Up</a>
            </div>
            <?php endif; ?>
            
            <button class="mobile-menu-toggle">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>
    
    <div class="mobile-nav">
        <ul class="mobile-nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="bmi-calculator.php">BMI Calculator</a></li>
            <li><a href="exercises.php">Exercises</a></li>
            <li><a href="forum.php">Community</a></li>
            <li><a href="dietary-meal-plans.php">Meal Plans</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
            <li><a href="login.php">Log In</a></li>
            <li><a href="register.php">Sign Up</a></li>
            <?php endif; ?>
        </ul>
    </div>
</header>