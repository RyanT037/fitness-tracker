<?php
session_start();
include 'includes/db_connect.php';
include 'includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Check if required parameters are provided
if (!isset($_POST['exercise_id']) || !isset($_POST['duration'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit;
}

$userId = $_SESSION['user_id'];
$exerciseId = filter_input(INPUT_POST, 'exercise_id', FILTER_VALIDATE_INT);
$duration = filter_input(INPUT_POST, 'duration', FILTER_VALIDATE_INT);
$notes = isset($_POST['notes']) ? filter_input(INPUT_POST, 'notes', FILTER_SANITIZE_STRING) : null;

// Validate inputs
if (!$exerciseId || !$duration) {
    echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
    exit;
}

// Insert workout record
$stmt = $conn->prepare("INSERT INTO workout_history (user_id, exercise_id, duration, notes, date) VALUES (?, ?, ?, ?, NOW())");
$stmt->bind_param("iiis", $userId, $exerciseId, $duration, $notes);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Workout saved successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error saving workout: ' . $conn->error]);
}

$stmt->close();
$conn->close();
?>