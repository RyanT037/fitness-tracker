<?php
session_start();
include 'includes/db_connect.php';

// Example fitness tips (could be loaded from a database in the future)
$fitnessTips = [
    [
        'title' => 'Stay Consistent',
        'tip' => 'Consistency is key to achieving your fitness goals. Set a schedule and stick to it!'
    ],
    [
        'title' => 'Hydrate',
        'tip' => 'Drink plenty of water throughout the day to stay hydrated and support your workouts.'
    ],
    [
        'title' => 'Balanced Diet',
        'tip' => 'Eat a balanced diet with plenty of fruits, vegetables, lean proteins, and whole grains.'
    ],
    [
        'title' => 'Rest & Recovery',
        'tip' => 'Allow your body time to recover with rest days and enough sleep each night.'
    ],
    [
        'title' => 'Warm Up & Cool Down',
        'tip' => 'Always warm up before exercising and cool down afterwards to prevent injury.'
    ],
    [
        'title' => 'Track Progress',
        'tip' => 'Keep a log of your workouts and nutrition to monitor your progress and stay motivated.'
    ],
    [
        'title' => 'Set Realistic Goals',
        'tip' => 'Set achievable short-term and long-term goals to keep yourself motivated.'
    ],
    [
        'title' => 'Mix It Up',
        'tip' => 'Try different types of exercises to keep things interesting and challenge your body.'
    ]
];

// Optionally, shuffle tips for variety
shuffle($fitnessTips);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Tips - FitTrack</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <main class="tips-container">
        <h1>Fitness Tips</h1>
        <div class="tips-list">
            <?php foreach ($fitnessTips as $tip): ?>
                <div class="tip-card">
                    <h2><?php echo htmlspecialchars($tip['title']); ?></h2>
                    <p><?php echo htmlspecialchars($tip['tip']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
