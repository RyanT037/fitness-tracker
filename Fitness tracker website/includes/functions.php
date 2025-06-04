<?php
/**
 * Calculate BMI based on weight (kg) and height (m)
 * 
 * @param float $weight Weight in kilograms
 * @param float $height Height in meters
 * @return float Calculated BMI value
 */
function calculateBMI($weight, $height) {
    if ($height <= 0 || $weight <= 0) {
        return 0;
    }
    return $weight / ($height * $height);
}

/**
 * Get BMI category based on BMI value
 * 
 * @param float $bmi BMI value
 * @return string BMI category
 */
function getBMICategory($bmi) {
    if ($bmi < 18.5) {
        return 'Underweight';
    } elseif ($bmi >= 18.5 && $bmi < 25) {
        return 'Normal weight';
    } elseif ($bmi >= 25 && $bmi < 30) {
        return 'Overweight';
    } else {
        return 'Obese';
    }
}

/**
 * Format date to readable string
 * 
 * @param string $date Date string
 * @param string $format Format string
 * @return string Formatted date
 */
function formatDate($date, $format = 'M j, Y') {
    return date($format, strtotime($date));
}

/**
 * Truncate text to a specified length
 * 
 * @param string $text Text to truncate
 * @param int $length Maximum length
 * @param string $suffix Suffix to add when truncated
 * @return string Truncated text
 */
function truncateText($text, $length = 100, $suffix = '...') {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . $suffix;
}

/**
 * Get time ago string (e.g., "2 hours ago")
 * 
 * @param string $datetime Date/time string
 * @return string Time ago string
 */
function timeAgo($datetime) {
    $time = strtotime($datetime);
    $now = time();
    $diff = $now - $time;
    
    if ($diff < 60) {
        return 'just now';
    } elseif ($diff < 3600) {
        $minutes = floor($diff / 60);
        return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 604800) {
        $days = floor($diff / 86400);
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 2592000) {
        $weeks = floor($diff / 604800);
        return $weeks . ' week' . ($weeks > 1 ? 's' : '') . ' ago';
    } else {
        return date('M j, Y', $time);
    }
}

/**
 * Generate pagination links
 * 
 * @param int $currentPage Current page number
 * @param int $totalPages Total pages
 * @param string $urlPattern URL pattern with :page placeholder
 * @return string HTML pagination links
 */
function generatePagination($currentPage, $totalPages, $urlPattern) {
    if ($totalPages <= 1) {
        return '';
    }
    
    $links = '<div class="pagination">';
    
    // Previous page link
    if ($currentPage > 1) {
        $links .= '<a href="' . str_replace(':page', $currentPage - 1, $urlPattern) . '" class="page-link">&laquo; Previous</a>';
    } else {
        $links .= '<span class="page-link disabled">&laquo; Previous</span>';
    }
    
    // Page number links
    $startPage = max(1, $currentPage - 2);
    $endPage = min($totalPages, $currentPage + 2);
    
    if ($startPage > 1) {
        $links .= '<a href="' . str_replace(':page', 1, $urlPattern) . '" class="page-link">1</a>';
        if ($startPage > 2) {
            $links .= '<span class="page-ellipsis">...</span>';
        }
    }
    
    for ($i = $startPage; $i <= $endPage; $i++) {
        if ($i == $currentPage) {
            $links .= '<span class="page-link active">' . $i . '</span>';
        } else {
            $links .= '<a href="' . str_replace(':page', $i, $urlPattern) . '" class="page-link">' . $i . '</a>';
        }
    }
    
    if ($endPage < $totalPages) {
        if ($endPage < $totalPages - 1) {
            $links .= '<span class="page-ellipsis">...</span>';
        }
        $links .= '<a href="' . str_replace(':page', $totalPages, $urlPattern) . '" class="page-link">' . $totalPages . '</a>';
    }
    
    // Next page link
    if ($currentPage < $totalPages) {
        $links .= '<a href="' . str_replace(':page', $currentPage + 1, $urlPattern) . '" class="page-link">Next &raquo;</a>';
    } else {
        $links .= '<span class="page-link disabled">Next &raquo;</span>';
    }
    
    $links .= '</div>';
    
    return $links;
}

/**
 * Create a secure random token
 * 
 * @param int $length Token length
 * @return string Random token
 */
function generateToken($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

/**
 * Validate email format
 * 
 * @param string $email Email to validate
 * @return bool Whether email is valid
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Get recommended exercises based on user profile
 * 
 * @param array $userProfile User profile data
 * @param mysqli $conn Database connection
 * @param int $limit Maximum number of exercises to return
 * @return array Recommended exercises
 */
function getRecommendedExercises($userProfile, $conn, $limit = 3) {
    $exercises = [];
    
    // Default recommendations if no profile data
    if (empty($userProfile['fitness_goal'])) {
        $sql = "SELECT * FROM exercises ORDER BY RAND() LIMIT ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $limit);
    } else {
        // Customize based on fitness goal
        $categoryId = 1; // Default to cardio
        
        switch(strtolower($userProfile['fitness_goal'])) {
            case 'weight loss':
                $categoryId = 1; // Cardio
                break;
            case 'muscle gain':
                $categoryId = 2; // Strength
                break;
            case 'flexibility':
                $categoryId = 3; // Flexibility
                break;
            case 'balance':
                $categoryId = 4; // Balance
                break;
        }
        
        $sql = "SELECT * FROM exercises WHERE category_id = ? ORDER BY RAND() LIMIT ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $categoryId, $limit);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $exercises[] = $row;
        }
    }
    
    $stmt->close();
    
    return $exercises;
}
?>