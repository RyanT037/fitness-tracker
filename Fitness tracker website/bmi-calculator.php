<?php
session_start();
include 'includes/db_connect.php';
include 'includes/functions.php';

$bmi = 0;
$bmiCategory = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $weight = isset($_POST['weight']) ? filter_var($_POST['weight'], FILTER_VALIDATE_FLOAT) : 0;
    $height = isset($_POST['height']) ? filter_var($_POST['height'], FILTER_VALIDATE_FLOAT) : 0;
    $heightUnit = isset($_POST['height-unit']) ? $_POST['height-unit'] : 'cm';
    
    if ($weight && $height) {
        // Convert height to meters if in cm
        if ($heightUnit === 'cm') {
            $heightInMeters = $height / 100;
        } else {
            $heightInMeters = $height;
        }
        
        // Calculate BMI
        $bmi = $weight / ($heightInMeters * $heightInMeters);
        
        // Determine BMI category
        if ($bmi < 18.5) {
            $bmiCategory = 'Underweight';
        } elseif ($bmi >= 18.5 && $bmi < 25) {
            $bmiCategory = 'Normal weight';
        } elseif ($bmi >= 25 && $bmi < 30) {
            $bmiCategory = 'Overweight';
        } else {
            $bmiCategory = 'Obese';
        }
        
        // Save BMI data if user is logged in
        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $date = date('Y-m-d');
            $stmt = $conn->prepare("INSERT INTO bmi_records (user_id, weight, height, bmi, date) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iddds", $userId, $weight, $heightInMeters, $bmi, $date);
            if ($stmt->execute()) {
                // Update user_profiles with latest weight, height
                $stmt2 = $conn->prepare("SELECT user_id FROM user_profiles WHERE user_id = ?");
                $stmt2->bind_param("i", $userId);
                $stmt2->execute();
                $result2 = $stmt2->get_result();
                if ($result2->num_rows > 0) {
                    $stmt3 = $conn->prepare("UPDATE user_profiles SET weight = ?, height = ? WHERE user_id = ?");
                    $stmt3->bind_param("ddi", $weight, $heightInMeters, $userId);
                    $stmt3->execute();
                    $stmt3->close();
                } else {
                    $stmt3 = $conn->prepare("INSERT INTO user_profiles (user_id, height, weight) VALUES (?, ?, ?)");
                    $stmt3->bind_param("idd", $userId, $heightInMeters, $weight);
                    $stmt3->execute();
                    $stmt3->close();
                }
                $stmt2->close();
                $message = "BMI calculation saved to your profile!";
            } else {
                $message = "Error saving BMI data: " . $conn->error;
            }
            $stmt->close();
        }
    } else {
        $message = "Please enter valid weight and height values.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BMI Calculator - FitTrack</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bmi.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="container">
        <section class="bmi-section">
            <div class="bmi-container">
                <div class="bmi-form-container">
                    <h1>BMI Calculator</h1>
                    <p>Calculate your Body Mass Index to understand your weight in relation to your height.</p>
                    
                    <form action="bmi-calculator.php" method="post" class="bmi-form">
                        <div class="form-group">
                            <label for="weight">Weight (kg)</label>
                            <input type="number" id="weight" name="weight" step="0.1" min="20" max="300" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="height">Height</label>
                            <div class="height-input-group">
                                <input type="number" id="height" name="height" step="0.01" min="50" max="300" required>
                                <select name="height-unit" id="height-unit">
                                    <option value="cm">cm</option>
                                    <option value="m">m</option>
                                </select>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Calculate BMI</button>
                    </form>
                </div>
                
                <?php if ($bmi > 0): ?>
                <div class="bmi-result">
                    <h2>Your BMI Result</h2>
                    <div class="bmi-score <?php echo strtolower($bmiCategory); ?>">
                        <span class="bmi-value"><?php echo number_format($bmi, 1); ?></span>
                        <span class="bmi-category"><?php echo $bmiCategory; ?></span>
                    </div>
                    
                    <div class="bmi-interpretation">
                        <h3>What Your BMI Means</h3>
                        <?php if ($bmiCategory === 'Underweight'): ?>
                            <p>A BMI of less than 18.5 indicates that you may be underweight. Consider consulting with a healthcare provider about achieving a healthy weight.</p>
                        <?php elseif ($bmiCategory === 'Normal weight'): ?>
                            <p>A BMI between 18.5 and 24.9 indicates that you are at a healthy weight. Maintain your current lifestyle with balanced diet and regular exercise.</p>
                        <?php elseif ($bmiCategory === 'Overweight'): ?>
                            <p>A BMI between 25 and 29.9 indicates that you may be overweight. Consider increasing physical activity and improving your diet.</p>
                        <?php elseif ($bmiCategory === 'Obese'): ?>
                            <p>A BMI of 30 or higher indicates obesity. It's recommended to consult with a healthcare provider about strategies for achieving a healthier weight.</p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="bmi-actions">
                        <a href="exercises.php" class="btn btn-secondary">Explore Exercises</a>
                        <?php if (!isset($_SESSION['user_id'])): ?>
                            <a href="register.php" class="btn btn-outline">Sign Up to Track Progress</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($message): ?>
                    <div class="message <?php echo strpos($message, 'Error') !== false ? 'error' : 'success'; ?>">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        
        <section class="bmi-info">
            <h2>Understanding BMI</h2>
            <div class="bmi-categories">
                <div class="bmi-category-card underweight">
                    <h3>Underweight</h3>
                    <p>BMI less than 18.5</p>
                </div>
                <div class="bmi-category-card normal">
                    <h3>Normal weight</h3>
                    <p>BMI 18.5 to 24.9</p>
                </div>
                <div class="bmi-category-card overweight">
                    <h3>Overweight</h3>
                    <p>BMI 25 to 29.9</p>
                </div>
                <div class="bmi-category-card obese">
                    <h3>Obese</h3>
                    <p>BMI 30 or higher</p>
                </div>
            </div>
            
            <div class="bmi-limitations">
                <h3>Limitations of BMI</h3>
                <p>While BMI is a useful measure of healthy weight, it does have some limitations:</p>
                <ul>
                    <li>It doesn't distinguish between weight from muscle and weight from fat</li>
                    <li>It may not be appropriate for athletes, elderly individuals, or pregnant women</li>
                    <li>It doesn't account for factors like body composition or fat distribution</li>
                </ul>
                <p>Always consult with a healthcare professional for a complete assessment of your health.</p>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>
    
    <script src="js/script.js"></script>
    <script src="js/bmi.js"></script>
</body>
</html>