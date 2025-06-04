<?php
session_start();
include 'includes/db_connect.php';
include 'includes/functions.php';

// Get exercise categories
$categories = [];
$sql = "SELECT * FROM exercise_categories ORDER BY name";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
} else {
    // Default categories if none in database
    $categories = [
        ['id' => 1, 'name' => 'Cardio', 'icon' => 'fa-heart-pulse'],
        ['id' => 2, 'name' => 'Strength', 'icon' => 'fa-dumbbell'],
        ['id' => 3, 'name' => 'Flexibility', 'icon' => 'fa-person-walking'],
        ['id' => 4, 'name' => 'Balance', 'icon' => 'fa-coins']
    ];
}

// Get exercises (default to first category if none selected)
$selectedCategory = isset($_GET['category']) ? intval($_GET['category']) : (isset($categories[0]['id']) ? $categories[0]['id'] : 1);
$exercises = [];

if (isset($selectedCategory)) {
    $sql = "SELECT * FROM exercises WHERE category_id = ? ORDER BY name";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("i", $selectedCategory);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $exercises[] = $row;
            }
        }
        $stmt->close();
    }
}

// If no exercises found in database, use defaults
if (empty($exercises)) {
    // Example exercises for different categories
    $defaultExercises = [
        1 => [ // Cardio
            ['id' => 1, 'name' => 'Jumping Jacks', 'description' => 'A full body exercise that primarily targets the quads.', 'image_url' => 'https://images.pexels.com/photos/4162487/pexels-photo-4162487.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1', 'duration' => 20],
            ['id' => 2, 'name' => 'High Knees', 'description' => 'A cardio exercise that strengthens the legs and core.', 'image_url' => 'https://images.pexels.com/photos/4162579/pexels-photo-4162579.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1', 'duration' => 20],
            ['id' => 3, 'name' => 'Burpees', 'description' => 'A full body exercise that builds strength and endurance.', 'image_url' => 'https://images.pexels.com/photos/28054/pexels-photo.jpg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1', 'duration' => 20]
        ],
        2 => [ // Strength
            ['id' => 4, 'name' => 'Squats', 'description' => 'A lower body exercise that primarily targets the quads, hamstrings, and glutes.', 'image_url' => 'https://images.pexels.com/photos/4162449/pexels-photo-4162449.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1', 'duration' => 20],
            ['id' => 5, 'name' => 'Lunges', 'description' => 'A unilateral exercise that works your legs and improves balance.', 'image_url' => 'https://images.pexels.com/photos/4498482/pexels-photo-4498482.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1', 'duration' => 20],
            ['id' => 6, 'name' => 'Push-ups', 'description' => 'An upper body exercise that works your chest, shoulders, and triceps.', 'image_url' => 'https://images.pexels.com/photos/4162454/pexels-photo-4162454.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1', 'duration' => 20]
        ],
        3 => [ // Flexibility
            ['id' => 7, 'name' => 'Standing Hamstring Stretch', 'description' => 'Stretches the hamstrings and lower back.', 'image_url' => 'https://images.pexels.com/photos/4056535/pexels-photo-4056535.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1', 'duration' => 20],
            ['id' => 8, 'name' => 'Butterfly Stretch', 'description' => 'Opens the hips and stretches the inner thighs.', 'image_url' => 'https://images.pexels.com/photos/4662438/pexels-photo-4662438.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1', 'duration' => 20],
            ['id' => 9, 'name' => 'Child\'s Pose', 'description' => 'Stretches the back, hips, and thighs.', 'image_url' => 'https://images.pexels.com/photos/3822856/pexels-photo-3822856.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1', 'duration' => 20]
        ],
        4 => [ // Balance
            ['id' => 10, 'name' => 'Single Leg Stand', 'description' => 'Improves balance and stability.', 'image_url' => 'https://images.pexels.com/photos/4498574/pexels-photo-4498574.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1', 'duration' => 20],
            ['id' => 11, 'name' => 'Tree Pose', 'description' => 'A yoga pose that improves balance and concentration.', 'image_url' => 'https://images.pexels.com/photos/3822906/pexels-photo-3822906.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1', 'duration' => 20],
            ['id' => 12, 'name' => 'Plank', 'description' => 'Strengthens the core and improves stability.', 'image_url' => 'https://images.pexels.com/photos/4162456/pexels-photo-4162456.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1', 'duration' => 20]
        ]
    ];
    
    $exercises = isset($defaultExercises[$selectedCategory]) ? $defaultExercises[$selectedCategory] : $defaultExercises[1];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercise Library - FitTrack</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/exercises.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="container">
        <section class="exercises-header">
            <h1>Exercise Library</h1>
            <p>Discover exercises with guided demonstrations and timers to improve your fitness</p>
        </section>
        
        <section class="category-tabs">
            <div class="tabs-container">
                <?php foreach ($categories as $category): ?>
                <a href="?category=<?php echo $category['id']; ?>" class="category-tab <?php echo ($selectedCategory == $category['id']) ? 'active' : ''; ?>">
                    <i class="fas <?php echo $category['icon']; ?>"></i>
                    <span><?php echo htmlspecialchars($category['name']); ?></span>
                </a>
                <?php endforeach; ?>
            </div>
        </section>
        
        <section class="exercises-grid">
            <?php foreach ($exercises as $exercise): ?>
            <div class="exercise-card" data-exercise-id="<?php echo $exercise['id']; ?>" data-duration="<?php echo $exercise['duration']; ?>">
                <div class="exercise-video">
                    <!-- Replace image with vector video demonstration -->
                    <video width="100%" height="180" controls poster="">
                        <source src="videos/<?php echo strtolower(str_replace(' ', '-', $exercise['name'])); ?>.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <div class="exercise-info">
                    <h3><?php echo htmlspecialchars($exercise['name']); ?></h3>
                    <p><?php echo htmlspecialchars($exercise['description']); ?></p>
                    <button class="btn btn-primary start-exercise">Start Exercise</button>
                </div>
            </div>
            <?php endforeach; ?>
        </section>
        <!-- Exercise Modal -->
        <div id="exercise-modal" class="modal">
            <div class="modal-content">
                <span class="close-modal">&times;</span>
                <div id="exercise-demo">
                    <h2 id="modal-exercise-name"></h2>
                    <div class="exercise-visual">
                        <!-- Replace modal image with vector video demonstration -->
                        <video id="modal-exercise-video" width="100%" height="240" controls poster="">
                            <source id="modal-exercise-video-src" src="" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        <div class="timer-container">
                            <div class="timer" id="exercise-timer">20</div>
                            <div class="timer-controls">
                                <button id="start-timer" class="btn btn-primary">Start</button>
                                <button id="reset-timer" class="btn btn-secondary">Reset</button>
                            </div>
                        </div>
                    </div>
                    <p id="modal-exercise-description"></p>
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="exercise-complete-actions">
                        <button id="save-exercise" class="btn btn-success">Save to Workout History</button>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <section class="workout-tips">
            <h2>Workout Tips</h2>
            <div class="tips-container">
                <div class="tip-card">
                    <i class="fas fa-heart"></i>
                    <h3>Warm Up First</h3>
                    <p>Always start with 5-10 minutes of light activity to prepare your body for exercise.</p>
                </div>
                <div class="tip-card">
                    <i class="fas fa-tint"></i>
                    <h3>Stay Hydrated</h3>
                    <p>Drink water before, during, and after your workout to maintain performance.</p>
                </div>
                <div class="tip-card">
                    <i class="fas fa-check-double"></i>
                    <h3>Proper Form</h3>
                    <p>Focus on correct technique to prevent injuries and maximize results.</p>
                </div>
                <div class="tip-card">
                    <i class="fas fa-bed"></i>
                    <h3>Rest and Recover</h3>
                    <p>Allow your body time to recover between intense workout sessions.</p>
                </div>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>
    
    <script src="js/script.js"></script>
    <script src="js/exercises.js"></script>
    <script>
    // Update modal to use video source dynamically
    const exerciseCards = document.querySelectorAll('.exercise-card');
    const modalVideo = document.getElementById('modal-exercise-video');
    const modalVideoSrc = document.getElementById('modal-exercise-video-src');
    const modalExerciseName = document.getElementById('modal-exercise-name');
    const modalExerciseDescription = document.getElementById('modal-exercise-description');
    const modal = document.getElementById('exercise-modal');
    const closeModal = document.querySelector('.close-modal');

    exerciseCards.forEach(card => {
        card.querySelector('.start-exercise').addEventListener('click', function() {
            const name = card.querySelector('h3').textContent;
            const desc = card.querySelector('p').textContent;
            const videoFile = 'videos/' + name.toLowerCase().replace(/ /g, '-') + '.mp4';
            modalExerciseName.textContent = name;
            modalExerciseDescription.textContent = desc;
            modalVideoSrc.src = videoFile;
            modalVideo.load();
            modal.style.display = 'block';
        });
    });
    closeModal.onclick = function() { modal.style.display = 'none'; modalVideo.pause(); };
    window.onclick = function(event) { if (event.target == modal) { modal.style.display = 'none'; modalVideo.pause(); } };
    </script>
</body>
</html>