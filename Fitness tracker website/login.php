<?php
session_start();
include 'includes/db_connect.php';
include 'includes/functions.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = filter_input(INPUT_POST, 'identifier', FILTER_SANITIZE_STRING);
    $password = $_POST['password'];
    
    if (empty($identifier) || empty($password)) {
        $error = "All fields are required";
    } else {
        // Check if identifier is username or email
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $identifier, $identifier);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                // Update last login
                $updateStmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
                $updateStmt->bind_param("i", $user['id']);
                $updateStmt->execute();
                $updateStmt->close();
                
                // Redirect to dashboard
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Invalid credentials";
            }
        } else {
            $error = "Invalid credentials";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FitTrack</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>Welcome Back</h1>
                <p>Log in to continue your fitness journey</p>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form action="login.php" method="post" class="auth-form">
                <div class="form-group">
                    <label for="identifier">Username or Email</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="identifier" name="identifier" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" required>
                        <i class="fas fa-eye toggle-password"></i>
                    </div>
                </div>
                
                <div class="form-options">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="forgot-password.php" class="forgot-password">Forgot Password?</a>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">Log In</button>
            </form>
            
            <div class="auth-footer">
                <p>Don't have an account? <a href="register.php">Sign Up</a></p>
            </div>
        </div>
        
        <div class="auth-features">
            <div class="feature">
                <i class="fas fa-calculator"></i>
                <h3>Track Your BMI</h3>
                <p>Monitor your Body Mass Index over time</p>
            </div>
            <div class="feature">
                <i class="fas fa-dumbbell"></i>
                <h3>Custom Workouts</h3>
                <p>Create and save personalized workout routines</p>
            </div>
            <div class="feature">
                <i class="fas fa-chart-line"></i>
                <h3>Progress Tracking</h3>
                <p>View your fitness journey with visual charts</p>
            </div>
            <div class="feature">
                <i class="fas fa-users"></i>
                <h3>Community Support</h3>
                <p>Connect with others on the same journey</p>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
    
    <script src="js/script.js"></script>
    <script src="js/auth.js"></script>
</body>
</html>