<?php
session_start();
include 'includes/db_connect.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

// Handle form submission
$success = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $height = isset($_POST['height']) ? floatval($_POST['height']) : null;
    $weight = isset($_POST['weight']) ? floatval($_POST['weight']) : null;
    $fitness_goal = isset($_POST['fitness_goal']) ? trim($_POST['fitness_goal']) : '';
    
    if ($height && $weight && $fitness_goal) {
        // Update or insert user profile
        $stmt = $conn->prepare("SELECT user_id FROM user_profiles WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // Update
            $stmt = $conn->prepare("UPDATE user_profiles SET height = ?, weight = ?, fitness_goal = ? WHERE user_id = ?");
            $stmt->bind_param("ddsi", $height, $weight, $fitness_goal, $userId);
        } else {
            // Insert
            $stmt = $conn->prepare("INSERT INTO user_profiles (user_id, height, weight, fitness_goal) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("idds", $userId, $height, $weight, $fitness_goal);
        }
        if ($stmt->execute()) {
            $success = true;
        } else {
            $error = 'Failed to update profile.';
        }
        $stmt->close();
    } else {
        $error = 'Please fill in all fields.';
    }
}

// Get current profile data
$stmt = $conn->prepare("
    SELECT u.username, up.height, up.weight, up.fitness_goal
    FROM users u
    LEFT JOIN user_profiles up ON u.id = up.user_id
    WHERE u.id = ?
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$profile = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - FitTrack</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <main class="profile-container">
        <h1>Edit Profile</h1>
        <?php if ($success): ?>
            <div class="alert success">Profile updated successfully!</div>
        <?php elseif ($error): ?>
            <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="post" class="profile-form">
            <div class="form-group">
                <label>Username</label>
                <input type="text" value="<?php echo htmlspecialchars($profile['username']); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="height">Height (cm)</label>
                <input type="number" step="0.01" name="height" id="height" value="<?php echo isset($profile['height']) ? htmlspecialchars($profile['height']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="weight">Weight (kg)</label>
                <input type="number" step="0.1" name="weight" id="weight" value="<?php echo isset($profile['weight']) ? htmlspecialchars($profile['weight']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="fitness_goal">Fitness Goal</label>
                <input type="text" name="fitness_goal" id="fitness_goal" value="<?php echo isset($profile['fitness_goal']) ? htmlspecialchars($profile['fitness_goal']) : ''; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </main>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
