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
    $calories = isset($_POST['calories']) ? intval($_POST['calories']) : null;
    $protein = isset($_POST['protein']) ? floatval($_POST['protein']) : null;
    $carbs = isset($_POST['carbs']) ? floatval($_POST['carbs']) : null;
    $fat = isset($_POST['fat']) ? floatval($_POST['fat']) : null;
    $date = date('Y-m-d');

    if ($calories !== null && $protein !== null && $carbs !== null && $fat !== null) {
        // Insert nutrition log
        $stmt = $conn->prepare("INSERT INTO nutrition_logs (user_id, date, calories, protein, carbs, fat) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isdddd", $userId, $date, $calories, $protein, $carbs, $fat);
        if ($stmt->execute()) {
            $success = true;
        } else {
            $error = 'Failed to log nutrition.';
        }
        $stmt->close();
    } else {
        $error = 'Please fill in all fields.';
    }
}

// Get recent nutrition logs
$nutritionLogs = [];
$stmt = $conn->prepare("SELECT * FROM nutrition_logs WHERE user_id = ? ORDER BY date DESC LIMIT 7");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $nutritionLogs[] = $row;
    }
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutrition Log - FitTrack</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <main class="nutrition-container">
        <h1>Nutrition Log</h1>
        <?php if ($success): ?>
            <div class="alert success">Nutrition logged successfully!</div>
        <?php elseif ($error): ?>
            <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="post" class="nutrition-form">
            <div class="form-group">
                <label for="calories">Calories</label>
                <input type="number" name="calories" id="calories" required>
            </div>
            <div class="form-group">
                <label for="protein">Protein (g)</label>
                <input type="number" step="0.1" name="protein" id="protein" required>
            </div>
            <div class="form-group">
                <label for="carbs">Carbs (g)</label>
                <input type="number" step="0.1" name="carbs" id="carbs" required>
            </div>
            <div class="form-group">
                <label for="fat">Fat (g)</label>
                <input type="number" step="0.1" name="fat" id="fat" required>
            </div>
            <button type="submit" class="btn btn-primary">Log Nutrition</button>
        </form>
        <h2>Recent Nutrition Logs</h2>
        <?php if (empty($nutritionLogs)): ?>
            <p>No nutrition logs yet.</p>
        <?php else: ?>
            <table class="nutrition-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Calories</th>
                        <th>Protein (g)</th>
                        <th>Carbs (g)</th>
                        <th>Fat (g)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($nutritionLogs as $log): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($log['date']); ?></td>
                            <td><?php echo htmlspecialchars($log['calories']); ?></td>
                            <td><?php echo htmlspecialchars($log['protein']); ?></td>
                            <td><?php echo htmlspecialchars($log['carbs']); ?></td>
                            <td><?php echo htmlspecialchars($log['fat']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
