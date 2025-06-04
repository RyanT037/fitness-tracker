<?php
session_start();
include 'includes/db_connect.php';
include 'includes/functions.php';

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);

// Handle post submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isLoggedIn) {
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);
    $userId = $_SESSION['user_id'];
    
    if ($title && $content) {
        $stmt = $conn->prepare("INSERT INTO forum_posts (user_id, title, content, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iss", $userId, $title, $content);
        
        if ($stmt->execute()) {
            $message = "Post created successfully!";
            // Redirect to avoid form resubmission
            header("Location: forum.php?success=1");
            exit;
        } else {
            $error = "Error creating post: " . $conn->error;
        }
        $stmt->close();
    } else {
        $error = "Title and content are required.";
    }
}

// Get success message from redirect
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $message = "Post created successfully!";
}

// Handle comments
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment']) && $isLoggedIn) {
    $postId = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);
    $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);
    $userId = $_SESSION['user_id'];
    
    if ($postId && $comment) {
        $stmt = $conn->prepare("INSERT INTO forum_comments (post_id, user_id, content, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iis", $postId, $userId, $comment);
        
        if ($stmt->execute()) {
            // Redirect to avoid form resubmission
            header("Location: forum.php?post=" . $postId . "&comment_success=1");
            exit;
        } else {
            $commentError = "Error posting comment: " . $conn->error;
        }
        $stmt->close();
    } else {
        $commentError = "Comment cannot be empty.";
    }
}

// Get specific post if post ID is provided
$singlePost = null;
$comments = [];

if (isset($_GET['post'])) {
    $postId = filter_input(INPUT_GET, 'post', FILTER_VALIDATE_INT);
    
    if ($postId) {
        $stmt = $conn->prepare("
            SELECT p.*, u.username, u.avatar, 
                   (SELECT COUNT(*) FROM forum_comments WHERE post_id = p.id) as comment_count
            FROM forum_posts p
            JOIN users u ON p.user_id = u.id
            WHERE p.id = ?
        ");
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            $singlePost = $result->fetch_assoc();
            
            // Get comments for this post
            $commentStmt = $conn->prepare("
                SELECT c.*, u.username, u.avatar
                FROM forum_comments c
                JOIN users u ON c.user_id = u.id
                WHERE c.post_id = ?
                ORDER BY c.created_at ASC
            ");
            $commentStmt->bind_param("i", $postId);
            $commentStmt->execute();
            $commentResult = $commentStmt->get_result();
            
            if ($commentResult && $commentResult->num_rows > 0) {
                while ($row = $commentResult->fetch_assoc()) {
                    $comments[] = $row;
                }
            }
            $commentStmt->close();
        }
        $stmt->close();
    }
}

// Get forum posts for main listing
if (!$singlePost) {
    $sql = "
        SELECT p.*, u.username, u.avatar, 
               (SELECT COUNT(*) FROM forum_comments WHERE post_id = p.id) as comment_count
        FROM forum_posts p
        JOIN users u ON p.user_id = u.id
        ORDER BY p.created_at DESC
        LIMIT 20
    ";
    $result = $conn->query($sql);
    $posts = [];
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Forum - FitTrack</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/forum.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="container">
        <section class="forum-header">
            <h1>Community Forum</h1>
            <p>Share your fitness journey, ask questions, and connect with other members</p>
            
            <?php if (!$isLoggedIn): ?>
                <div class="login-prompt">
                    <p>Please <a href="login.php">log in</a> or <a href="register.php">register</a> to participate in the forum.</p>
                </div>
            <?php else: ?>
                <button id="new-post-btn" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> New Post
                </button>
            <?php endif; ?>
        </section>
        
        <?php if (isset($message)): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (isset($_GET['comment_success']) && $_GET['comment_success'] == 1): ?>
            <div class="alert alert-success">Comment added successfully!</div>
        <?php endif; ?>
        
        <!-- New Post Form Modal -->
        <div id="post-modal" class="modal">
            <div class="modal-content">
                <span class="close-modal">&times;</span>
                <h2>Create New Post</h2>
                <form action="forum.php" method="post" class="post-form">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="content">Content</label>
                        <textarea id="content" name="content" rows="6" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Post</button>
                </form>
            </div>
        </div>
        
        <?php if ($singlePost): ?>
            <!-- Single Post View -->
            <section class="forum-content">
                <div class="post-navigation">
                    <a href="forum.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Posts
                    </a>
                </div>
                
                <article class="post post-full">
                    <div class="post-header">
                        <div class="post-author">
                            <img src="<?php echo !empty($singlePost['avatar']) ? htmlspecialchars($singlePost['avatar']) : 'images/default-avatar.png'; ?>" alt="Profile picture" class="author-avatar">
                            <div class="author-info">
                                <span class="author-name"><?php echo htmlspecialchars($singlePost['username']); ?></span>
                                <span class="post-date"><?php echo date('M j, Y', strtotime($singlePost['created_at'])); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="post-content">
                        <h2 class="post-title"><?php echo htmlspecialchars($singlePost['title']); ?></h2>
                        <div class="post-body">
                            <?php echo nl2br(htmlspecialchars($singlePost['content'])); ?>
                        </div>
                    </div>
                </article>
                
                <div class="comments-section">
                    <h3><?php echo count($comments); ?> Comments</h3>
                    
                    <?php if ($isLoggedIn): ?>
                        <form action="forum.php" method="post" class="comment-form">
                            <input type="hidden" name="post_id" value="<?php echo $singlePost['id']; ?>">
                            <div class="form-group">
                                <textarea name="comment" placeholder="Add a comment..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Post Comment</button>
                        </form>
                        
                        <?php if (isset($commentError)): ?>
                            <div class="alert alert-error"><?php echo $commentError; ?></div>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <div class="comments-list">
                        <?php if (empty($comments)): ?>
                            <p class="no-comments">No comments yet. Be the first to share your thoughts!</p>
                        <?php else: ?>
                            <?php foreach ($comments as $comment): ?>
                                <div class="comment">
                                    <div class="comment-author">
                                        <img src="<?php echo !empty($comment['avatar']) ? htmlspecialchars($comment['avatar']) : 'images/default-avatar.png'; ?>" alt="Profile picture" class="author-avatar">
                                        <div class="author-info">
                                            <span class="author-name"><?php echo htmlspecialchars($comment['username']); ?></span>
                                            <span class="comment-date"><?php echo date('M j, Y g:i a', strtotime($comment['created_at'])); ?></span>
                                        </div>
                                    </div>
                                    <div class="comment-content">
                                        <?php echo nl2br(htmlspecialchars($comment['content'])); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        <?php else: ?>
            <!-- Forum Posts Listing -->
            <section class="forum-content">
                <?php if (empty($posts)): ?>
                    <div class="no-posts">
                        <p>No posts found. Be the first to start a discussion!</p>
                    </div>
                <?php else: ?>
                    <div class="posts-list">
                        <?php foreach ($posts as $post): ?>
                            <article class="post">
                                <div class="post-header">
                                    <div class="post-author">
                                        <img src="<?php echo !empty($post['avatar']) ? htmlspecialchars($post['avatar']) : 'images/default-avatar.png'; ?>" alt="Profile picture" class="author-avatar">
                                        <div class="author-info">
                                            <span class="author-name"><?php echo htmlspecialchars($post['username']); ?></span>
                                            <span class="post-date"><?php echo date('M j, Y', strtotime($post['created_at'])); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="post-content">
                                    <h2 class="post-title">
                                        <a href="forum.php?post=<?php echo $post['id']; ?>">
                                            <?php echo htmlspecialchars($post['title']); ?>
                                        </a>
                                    </h2>
                                    <div class="post-preview">
                                        <?php 
                                        $previewText = htmlspecialchars(substr($post['content'], 0, 150));
                                        echo nl2br($previewText);
                                        if (strlen($post['content']) > 150) echo '...';
                                        ?>
                                    </div>
                                </div>
                                <div class="post-footer">
                                    <a href="forum.php?post=<?php echo $post['id']; ?>" class="comments-link">
                                        <i class="fas fa-comment"></i>
                                        <?php echo $post['comment_count']; ?> Comments
                                    </a>
                                    <a href="forum.php?post=<?php echo $post['id']; ?>" class="read-more">
                                        Read More <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
        <?php endif; ?>
        
        <section class="community-guidelines">
            <h2>Community Guidelines</h2>
            <div class="guidelines-container">
                <div class="guideline">
                    <i class="fas fa-heart"></i>
                    <h3>Be Respectful</h3>
                    <p>Treat others with kindness and respect. No harassment or bullying.</p>
                </div>
                <div class="guideline">
                    <i class="fas fa-check-circle"></i>
                    <h3>Stay On Topic</h3>
                    <p>Keep discussions related to fitness, health, and wellness.</p>
                </div>
                <div class="guideline">
                    <i class="fas fa-shield-alt"></i>
                    <h3>Protect Privacy</h3>
                    <p>Don't share personal information about yourself or others.</p>
                </div>
                <div class="guideline">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3>No Medical Advice</h3>
                    <p>Don't offer or solicit medical advice. Consult professionals.</p>
                </div>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>
    
    <script src="js/script.js"></script>
    <script src="js/forum.js"></script>
</body>
</html>