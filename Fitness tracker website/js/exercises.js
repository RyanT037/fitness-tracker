// Exercises Page JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Exercise modal functionality
    const exerciseCards = document.querySelectorAll('.exercise-card');
    const exerciseModal = document.getElementById('exercise-modal');
    const modalClose = document.querySelector('.close-modal');
    const modalExerciseName = document.getElementById('modal-exercise-name');
    const modalExerciseImage = document.getElementById('modal-exercise-image');
    const modalExerciseDescription = document.getElementById('modal-exercise-description');
    const exerciseTimer = document.getElementById('exercise-timer');
    const startTimerBtn = document.getElementById('start-timer');
    const resetTimerBtn = document.getElementById('reset-timer');
    const saveExerciseBtn = document.getElementById('save-exercise');
    
    let timerInterval;
    let currentExercise = null;
    
    // Open exercise modal
    if (exerciseCards.length > 0 && exerciseModal) {
        exerciseCards.forEach(card => {
            const startButton = card.querySelector('.start-exercise');
            
            startButton.addEventListener('click', function() {
                const exerciseId = card.getAttribute('data-exercise-id');
                const exerciseName = card.querySelector('h3').textContent;
                const exerciseImage = card.querySelector('img').src;
                const exerciseDescription = card.querySelector('p').textContent;
                const exerciseDuration = parseInt(card.getAttribute('data-duration')) || 20;
                
                // Set modal content
                modalExerciseName.textContent = exerciseName;
                modalExerciseImage.src = exerciseImage;
                modalExerciseImage.alt = exerciseName;
                modalExerciseDescription.textContent = exerciseDescription;
                exerciseTimer.textContent = exerciseDuration;
                
                // Store current exercise data
                currentExercise = {
                    id: exerciseId,
                    name: exerciseName,
                    duration: exerciseDuration
                };
                
                // Show modal
                exerciseModal.classList.add('active');
                document.body.style.overflow = 'hidden';
                
                // Reset timer state
                clearInterval(timerInterval);
                startTimerBtn.textContent = 'Start';
                startTimerBtn.classList.remove('btn-secondary');
                startTimerBtn.classList.add('btn-primary');
                exerciseTimer.style.color = 'var(--color-white)';
                exerciseTimer.textContent = exerciseDuration;
            });
        });
    }
    
    // Close exercise modal
    if (modalClose && exerciseModal) {
        modalClose.addEventListener('click', function() {
            exerciseModal.classList.remove('active');
            document.body.style.overflow = '';
            
            // Stop timer
            clearInterval(timerInterval);
        });
        
        // Close modal when clicking outside
        exerciseModal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
                document.body.style.overflow = '';
                
                // Stop timer
                clearInterval(timerInterval);
            }
        });
    }
    
    // Timer functionality
    if (startTimerBtn && resetTimerBtn && exerciseTimer) {
        startTimerBtn.addEventListener('click', function() {
            if (this.textContent === 'Start') {
                // Start timer
                let timeLeft = parseInt(exerciseTimer.textContent);
                
                timerInterval = setInterval(() => {
                    timeLeft--;
                    exerciseTimer.textContent = timeLeft;
                    
                    // Change color when time is running low
                    if (timeLeft <= 5) {
                        exerciseTimer.style.color = 'var(--color-error)';
                    }
                    
                    // Timer complete
                    if (timeLeft <= 0) {
                        clearInterval(timerInterval);
                        exerciseTimer.textContent = 'Done!';
                        
                        // Show notification
                        if ('Notification' in window && Notification.permission === 'granted') {
                            new Notification('Exercise Complete!', {
                                body: `You've completed ${currentExercise.name}!`,
                                icon: '/favicon.ico'
                            });
                        }
                    }
                }, 1000);
                
                // Update button
                this.textContent = 'Pause';
                this.classList.remove('btn-primary');
                this.classList.add('btn-secondary');
            } else {
                // Pause timer
                clearInterval(timerInterval);
                
                // Update button
                this.textContent = 'Start';
                this.classList.remove('btn-secondary');
                this.classList.add('btn-primary');
            }
        });
        
        resetTimerBtn.addEventListener('click', function() {
            // Reset timer
            clearInterval(timerInterval);
            exerciseTimer.textContent = currentExercise ? currentExercise.duration : 20;
            exerciseTimer.style.color = 'var(--color-white)';
            
            // Reset button
            startTimerBtn.textContent = 'Start';
            startTimerBtn.classList.remove('btn-secondary');
            startTimerBtn.classList.add('btn-primary');
        });
    }
    
    // Save exercise to workout history
    if (saveExerciseBtn) {
        saveExerciseBtn.addEventListener('click', function() {
            if (!currentExercise) return;
            
            // Send AJAX request to save exercise
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'save-workout.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            
            xhr.onload = function() {
                if (this.status === 200) {
                    const response = JSON.parse(this.responseText);
                    
                    if (response.success) {
                        alert('Exercise saved to your workout history!');
                    } else {
                        alert('Error: ' + response.message);
                    }
                }
            };
            
            xhr.send(`exercise_id=${currentExercise.id}&duration=${currentExercise.duration}`);
        });
    }
    
    // Request notification permission
    if ('Notification' in window && Notification.permission !== 'granted' && Notification.permission !== 'denied') {
        // Request permission when user interacts with the page
        document.addEventListener('click', function requestNotification() {
            Notification.requestPermission();
            document.removeEventListener('click', requestNotification);
        }, { once: true });
    }
});