<?php
session_start();
include 'includes/db_connect.php';

$faqs = [
    [
        'question' => 'How do I create an account?',
        'answer' => 'Click the Sign Up button on the homepage and fill in your details to create a new account.'
    ],
    [
        'question' => 'How do I reset my password?',
        'answer' => 'Go to the login page and click on the Forgot Password link. Follow the instructions to reset your password.'
    ],
    [
        'question' => 'How do I log my workouts?',
        'answer' => 'After logging in, go to the dashboard and use the workout logging feature to record your exercises.'
    ],
    [
        'question' => 'How do I update my profile information?',
        'answer' => 'Navigate to the Profile page from the dashboard or menu and update your details as needed.'
    ],
    [
        'question' => 'Is my data secure?',
        'answer' => 'We use secure protocols and best practices to keep your data safe. Your information is never shared without your consent.'
    ],
    [
        'question' => 'Can I track my nutrition?',
        'answer' => 'Yes, use the Nutrition Log page to record your daily calories, protein, carbs, and fat.'
    ],
    [
        'question' => 'How do I contact support?',
        'answer' => 'Use the contact form on the website or email us at support@fittrack.com.'
    ]
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - FitTrack</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/auth.css">
    <style>
        .faq-list { max-width: 700px; margin: 0 auto; }
        .faq-item { margin-bottom: 1.5em; }
        .faq-question { font-weight: bold; cursor: pointer; }
        .faq-answer { display: none; margin-top: 0.5em; }
        .faq-item.open .faq-answer { display: block; }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <main class="faq-container">
        <h1>Frequently Asked Questions</h1>
        <div class="faq-list">
            <?php foreach ($faqs as $faq): ?>
                <div class="faq-item">
                    <div class="faq-question"><?php echo htmlspecialchars($faq['question']); ?></div>
                    <div class="faq-answer"><?php echo htmlspecialchars($faq['answer']); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
    <?php include 'includes/footer.php'; ?>
    <script>
        document.querySelectorAll('.faq-question').forEach(function(q) {
            q.addEventListener('click', function() {
                var item = this.parentElement;
                item.classList.toggle('open');
            });
        });
    </script>
</body>
</html>
