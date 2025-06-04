<?php
session_start();
include 'includes/db_connect.php';
include 'includes/functions.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

// Get user data
$stmt = $conn->prepare("
    SELECT u.*, up.height, up.weight, up.fitness_goal
    FROM users u
    LEFT JOIN user_profiles up ON u.id = up.user_id
    WHERE u.id = ?
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Get BMI history
$bmiRecords = [];
$stmt = $conn->prepare("
    SELECT * FROM bmi_records
    WHERE user_id = ?
    ORDER BY date DESC
    LIMIT 10
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bmiRecords[] = $row;
    }
}
$stmt->close();

// Get workout history
$workoutHistory = [];
$stmt = $conn->prepare("
    SELECT w.*, e.name as exercise_name
    FROM workout_history w
    JOIN exercises e ON w.exercise_id = e.id
    WHERE w.user_id = ?
    ORDER BY w.date DESC
    LIMIT 5
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $workoutHistory[] = $row;
    }
}
$stmt->close();

// Get recent forum activity
$forumActivity = [];
$stmt = $conn->prepare("
    (
        SELECT 'post' as type, p.id, p.title, p.created_at
        FROM forum_posts p
        WHERE p.user_id = ?
        ORDER BY p.created_at DESC
        LIMIT 3
    )
    UNION
    (
        SELECT 'comment' as type, p.id, p.title, c.created_at
        FROM forum_comments c
        JOIN forum_posts p ON c.post_id = p.id
        WHERE c.user_id = ?
        ORDER BY c.created_at DESC
        LIMIT 3
    )
    ORDER BY created_at DESC
    LIMIT 5
");
$stmt->bind_param("ii", $userId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $forumActivity[] = $row;
    }
}
$stmt->close();

// Handle welcome message
$welcomeMessage = isset($_GET['welcome']) && $_GET['welcome'] == 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - FitTrack</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="dashboard-container">
        <?php if ($welcomeMessage): ?>
            <div class="welcome-message">
                <div class="welcome-content">
                    <h2>Welcome to FitTrack, <?php echo htmlspecialchars($user['username']); ?>!</h2>
                    <p>Your fitness journey starts now. Let's set up your profile to get personalized recommendations.</p>
                    <a href="profile.php" class="btn btn-primary">Complete Your Profile</a>
                    <button class="close-welcome"><i class="fas fa-times"></i></button>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="dashboard-header">
            <h1>Dashboard</h1>
            <div class="user-greeting">
                <p>Hello, <strong><?php echo htmlspecialchars($user['username']); ?></strong>!</p>
                <p class="last-login">Last login: <?php echo isset($user['last_login']) ? date('M j, Y g:i a', strtotime($user['last_login'])) : 'First login'; ?></p>
            </div>
        </div>
        
        <div class="dashboard-grid">
            <!-- Quick Stats Section -->
            <section class="dashboard-card stats-card">
                <h2>Quick Stats</h2>
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-icon"><i class="fas fa-weight"></i></div>
                        <div class="stat-info">
                            <h3>Current Weight</h3>
                            <p><?php echo isset($user['weight']) ? htmlspecialchars($user['weight']) . ' kg' : 'Not set'; ?></p>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon"><i class="fas fa-ruler-vertical"></i></div>
                        <div class="stat-info">
                            <h3>Height</h3>
                            <p><?php echo isset($user['height']) ? htmlspecialchars($user['height']) . ' m' : 'Not set'; ?></p>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon"><i class="fas fa-calculator"></i></div>
                        <div class="stat-info">
                            <h3>Current BMI</h3>
                            <?php
                            if (isset($user['weight']) && isset($user['height']) && $user['height'] > 0) {
                                $bmi = $user['weight'] / ($user['height'] * $user['height']);
                                echo '<p>' . number_format($bmi, 1) . '</p>';
                            } else {
                                echo '<p>Not available</p>';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon"><i class="fas fa-bullseye"></i></div>
                        <div class="stat-info">
                            <h3>Fitness Goal</h3>
                            <p><?php echo isset($user['fitness_goal']) ? htmlspecialchars($user['fitness_goal']) : 'Not set'; ?></p>
                        </div>
                    </div>
                </div>
                <div class="card-actions">
                    <a href="bmi-calculator.php" class="btn btn-secondary">Update BMI</a>
                    <a href="profile.php" class="btn btn-outline">Edit Profile</a>
                </div>
            </section>
            
            <!-- BMI Progress Chart -->
            <section class="dashboard-card chart-card">
                <h2>BMI Progress</h2>
                <?php if (empty($bmiRecords)): ?>
                    <div class="no-data">
                        <p>No BMI data available yet.</p>
                        <a href="bmi-calculator.php" class="btn btn-primary">Calculate BMI</a>
                    </div>
                <?php else: ?>
                    <div class="chart-container">
                        <canvas id="bmiChart"></canvas>
                    </div>
                <?php endif; ?>
            </section>
            
            <!-- Recent Activity -->
            <section class="dashboard-card activity-card">
                <h2>Recent Activity</h2>
                <div class="activity-tabs">
                    <button class="tab-btn active" data-tab="workouts">Workouts</button>
                    <button class="tab-btn" data-tab="forum">Forum</button>
                </div>
                
                <div class="tab-content" id="workouts-tab">
                    <?php if (empty($workoutHistory)): ?>
                        <div class="no-data">
                            <p>No workout history yet.</p>
                            <a href="exercises.php" class="btn btn-primary">Start Exercising</a>
                        </div>
                    <?php else: ?>
                        <ul class="activity-list">
                            <?php foreach ($workoutHistory as $workout): ?>
                                <li class="activity-item">
                                    <div class="activity-icon"><i class="fas fa-dumbbell"></i></div>
                                    <div class="activity-details">
                                        <h3><?php echo htmlspecialchars($workout['exercise_name']); ?></h3>
                                        <p>Completed on <?php echo date('M j, Y', strtotime($workout['date'])); ?></p>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="card-actions">
                            <a href="workout-history.php" class="btn btn-secondary">View All History</a>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="tab-content hidden" id="forum-tab">
                    <?php if (empty($forumActivity)): ?>
                        <div class="no-data">
                            <p>No forum activity yet.</p>
                            <a href="forum.php" class="btn btn-primary">Visit Forum</a>
                        </div>
                    <?php else: ?>
                        <ul class="activity-list">
                            <?php foreach ($forumActivity as $activity): ?>
                                <li class="activity-item">
                                    <div class="activity-icon">
                                        <i class="fas <?php echo $activity['type'] === 'post' ? 'fa-pen-to-square' : 'fa-comment'; ?>"></i>
                                    </div>
                                    <div class="activity-details">
                                        <h3>
                                            <?php echo $activity['type'] === 'post' ? 'Created a post' : 'Commented on'; ?>:
                                            <a href="forum.php?post=<?php echo $activity['id']; ?>">
                                                <?php echo htmlspecialchars($activity['title']); ?>
                                            </a>
                                        </h3>
                                        <p><?php echo date('M j, Y', strtotime($activity['created_at'])); ?></p>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="card-actions">
                            <a href="forum.php" class="btn btn-secondary">Visit Forum</a>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
            
            <!-- Recommended Exercises -->
            <section class="dashboard-card recommended-card">
                <h2>Recommended Exercises</h2>
                <div class="recommended-exercises">
                    <?php
                    // Example recommended exercises based on profile
                    $recommendedExercises = [
                        ['name' => 'Squats', 'description' => 'Great for building lower body strength', 'icon' => 'fa-dumbbell'],
                        ['name' => 'Cardio Walk', 'description' => '30 minutes at moderate pace', 'icon' => 'fa-walking'],
                        ['name' => 'Stretching Routine', 'description' => 'Improve flexibility and recovery', 'icon' => 'fa-person-walking']
                    ];
                    
                    foreach ($recommendedExercises as $exercise):
                    ?>
                        <div class="recommended-item">
                            <div class="recommended-icon"><i class="fas <?php echo $exercise['icon']; ?>"></i></div>
                            <div class="recommended-details">
                                <h3><?php echo $exercise['name']; ?></h3>
                                <p><?php echo $exercise['description']; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="card-actions">
                    <a href="exercises.php" class="btn btn-primary">Find More Exercises</a>
                </div>
            </section>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
    
    <script src="js/script.js"></script>
    <script src="js/dashboard.js"></script>
    
    <?php if (!empty($bmiRecords)): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bmiData = <?php 
                $chartData = array_reverse(array_map(function($record) {
                    return [
                        'date' => date('M j', strtotime($record['date'])),
                        'bmi' => round($record['bmi'], 1)
                    ];
                }, $bmiRecords));
                echo json_encode($chartData);
            ?>;
            
            const ctx = document.getElementById('bmiChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: bmiData.map(record => record.date),
                    datasets: [{
                        label: 'BMI',
                        data: bmiData.map(record => record.bmi),
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                        pointRadius: 4,
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: false,
                            suggestedMin: Math.min(...bmiData.map(record => record.bmi)) - 2,
                            suggestedMax: Math.max(...bmiData.map(record => record.bmi)) + 2,
                            ticks: {
                                precision: 1
                            }
                        }
                    }
                }
            });
        });
    </script>
    <?php endif; ?>
</body>
</html>