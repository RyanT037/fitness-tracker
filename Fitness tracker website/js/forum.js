// Forum Page JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // New post modal
    const newPostBtn = document.getElementById('new-post-btn');
    const postModal = document.getElementById('post-modal');
    
    if (newPostBtn && postModal) {
        newPostBtn.addEventListener('click', function() {
            postModal.classList.add('active');
            document.body.style.overflow = 'hidden';
            
            // Focus on title field
            setTimeout(() => {
                document.getElementById('title').focus();
            }, 100);
        });
    }
    
    // Post form validation
    const postForm = document.querySelector('.post-form');
    
    if (postForm) {
        postForm.addEventListener('submit', function(e) {
            const title = document.getElementById('title').value.trim();
            const content = document.getElementById('content').value.trim();
            
            if (title === '' || content === '') {
                e.preventDefault();
                alert('Please fill in all fields.');
            } else if (title.length < 5) {
                e.preventDefault();
                alert('Title must be at least 5 characters long.');
            } else if (content.length < 10) {
                e.preventDefault();
                alert('Content must be at least 10 characters long.');
            }
        });
    }
    
    // Comment form validation
    const commentForm = document.querySelector('.comment-form');
    
    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
            const comment = this.querySelector('textarea[name="comment"]').value.trim();
            
            if (comment === '') {
                e.preventDefault();
                alert('Please enter a comment.');
            } else if (comment.length < 3) {
                e.preventDefault();
                alert('Comment must be at least 3 characters long.');
            }
        });
    }
    
    // Auto resize textarea
    const textareas = document.querySelectorAll('textarea');
    
    if (textareas.length > 0) {
        textareas.forEach(textarea => {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
        });
    }
    
    // Smooth scroll to comments section if comment_success parameter is present
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('comment_success') && urlParams.get('comment_success') === '1') {
        const commentsSection = document.querySelector('.comments-section');
        
        if (commentsSection) {
            setTimeout(() => {
                commentsSection.scrollIntoView({ behavior: 'smooth' });
            }, 500);
        }
    }
    
    // Add highlight effect to new comments
    if (urlParams.has('comment_success') && urlParams.get('comment_success') === '1') {
        const comments = document.querySelectorAll('.comment');
        
        if (comments.length > 0) {
            const lastComment = comments[comments.length - 1];
            
            lastComment.style.backgroundColor = 'rgba(67, 97, 238, 0.1)';
            
            setTimeout(() => {
                lastComment.style.transition = 'background-color 1s ease-in-out';
                lastComment.style.backgroundColor = 'var(--color-white)';
            }, 100);
        }
    }
});