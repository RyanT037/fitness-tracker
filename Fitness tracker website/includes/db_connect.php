<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fittrack";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // Use a less detailed error message for security
    die("Database connection failed. Please try again later.");
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    // Select the database
    $conn->select_db($dbname);
    
    // Create users table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        last_login DATETIME NULL,
        avatar VARCHAR(255) NULL
    )";
    $conn->query($sql);
    
    // Create user_profiles table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS user_profiles (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT(11) UNSIGNED NOT NULL,
        height FLOAT NULL,
        weight FLOAT NULL,
        fitness_goal VARCHAR(100) NULL,
        activity_level VARCHAR(50) NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    $conn->query($sql);
    
    // Create bmi_records table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS bmi_records (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT(11) UNSIGNED NOT NULL,
        weight FLOAT NOT NULL,
        height FLOAT NOT NULL,
        bmi FLOAT NOT NULL,
        date DATE NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    $conn->query($sql);
    
    // Create exercise_categories table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS exercise_categories (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL,
        icon VARCHAR(50) NOT NULL
    )";
    $conn->query($sql);
    
    // Insert default categories if table is empty
    $sql = "SELECT COUNT(*) as count FROM exercise_categories";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    
    if ($row['count'] == 0) {
        $sql = "INSERT INTO exercise_categories (name, icon) VALUES 
            ('Cardio', 'fa-heart-pulse'),
            ('Strength', 'fa-dumbbell'),
            ('Flexibility', 'fa-person-walking'),
            ('Balance', 'fa-coins')";
        $conn->query($sql);
    }
    
    // Create exercises table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS exercises (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        category_id INT(11) UNSIGNED NOT NULL,
        name VARCHAR(100) NOT NULL,
        description TEXT NOT NULL,
        image_url VARCHAR(255) NOT NULL,
        duration INT(11) NOT NULL DEFAULT 20,
        FOREIGN KEY (category_id) REFERENCES exercise_categories(id) ON DELETE CASCADE
    )";
    $conn->query($sql);
    
    // Create workout_history table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS workout_history (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT(11) UNSIGNED NOT NULL,
        exercise_id INT(11) UNSIGNED NOT NULL,
        date DATETIME DEFAULT CURRENT_TIMESTAMP,
        duration INT(11) NOT NULL,
        notes TEXT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (exercise_id) REFERENCES exercises(id) ON DELETE CASCADE
    )";
    $conn->query($sql);
    
    // Create forum_posts table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS forum_posts (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT(11) UNSIGNED NOT NULL,
        title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    $conn->query($sql);
    
    // Create forum_comments table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS forum_comments (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        post_id INT(11) UNSIGNED NOT NULL,
        user_id INT(11) UNSIGNED NOT NULL,
        content TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (post_id) REFERENCES forum_posts(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    $conn->query($sql);
    
    // Create testimonials table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS testimonials (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        content TEXT NOT NULL,
        avatar VARCHAR(255) NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )";
    $conn->query($sql);
}
?>