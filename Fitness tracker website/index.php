<?php
session_start();
include 'includes/db_connect.php';
include 'includes/functions.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitTrack - Your Fitness Journey Starts Here</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-content">
                <h1>Transform Your Fitness Journey</h1>
                <p>Track progress, discover exercises, and connect with a community of fitness enthusiasts</p>
                <div class="hero-buttons">
                    <a href="bmi-calculator.php" class="btn btn-primary">Calculate BMI</a>
                    <a href="exercises.php" class="btn btn-secondary">Start Workout</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="https://images.pexels.com/photos/4498362/pexels-photo-4498362.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Fitness tracking">
            </div>
        </section>

        <!-- Features Section -->
        <section class="features">
            <h2>Your Complete Fitness Solution</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <i class="fas fa-calculator"></i>
                    <h3>BMI Calculator</h3>
                    <p>Calculate your Body Mass Index and understand your fitness level</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-dumbbell"></i>
                    <h3>Exercise Library</h3>
                    <p>Access a variety of exercises with visual demonstrations</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-stopwatch"></i>
                    <h3>Workout Timer</h3>
                    <p>Time your exercises with our built-in workout timer</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-users"></i>
                    <h3>Community Forum</h3>
                    <p>Share your journey and connect with other fitness enthusiasts</p>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="testimonials">
            <h2>Success Stories</h2>
            <div class="testimonial-slider">
                <?php
                // Fetch testimonials from database
                $sql = "SELECT * FROM testimonials ORDER BY RAND() LIMIT 3";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<div class="testimonial-card">';
                        echo '<div class="testimonial-content">"' . htmlspecialchars($row['content']) . '"</div>';
                        echo '<div class="testimonial-author">' . htmlspecialchars($row['name']) . '</div>';
                        echo '</div>';
                    }
                } else {
                    // Default testimonials if none in database
                    ?>
                    <div class="testimonial-card">
                        <div class="testimonial-content">"FitTrack helped me lose 15 pounds in just two months. The exercise guides are fantastic!"</div>
                        <div class="testimonial-author">Sarah J.</div>
                    </div>
                    <div class="testimonial-card">
                        <div class="testimonial-content">"I love the community aspect. Getting feedback and encouragement from others keeps me motivated."</div>
                        <div class="testimonial-author">Michael T.</div>
                    </div>
                    <div class="testimonial-card">
                        <div class="testimonial-content">"The BMI calculator and progress tracking features have been eye-opening. Highly recommend!"</div>
                        <div class="testimonial-author">Emily R.</div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="cta">
            <h2>Ready to Begin Your Fitness Journey?</h2>
            <p>Join thousands of users who have transformed their lives with FitTrack</p>
            <a href="register.php" class="btn btn-primary">Sign Up Now</a>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>
    
    <script src="js/script.js"></script>
    <script src="js/home.js"></script>
</body>
</html>