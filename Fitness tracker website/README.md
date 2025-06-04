# FitTrack Fitness Tracker Website

FitTrack is a PHP and MySQL-based fitness tracking web application. It allows users to log workouts, track BMI, manage nutrition, view exercise guides, and access meal plans and fitness tips. The project is designed for personal fitness management and can be used as a learning resource for web development with PHP and MySQL.

## Features
- User registration and login
- Dashboard with quick stats, BMI, and recent activity
- BMI calculator with history tracking
- Nutrition log with daily calories and macros
- Exercise library with categories and visual guides
- Workout history and progress tracking
- Dietary meal plans for various fitness goals
- Fitness tips and FAQ pages
- Responsive design for desktop and mobile

## Setup Instructions

### Prerequisites
- Windows OS (recommended for WAMP)
- [WAMP Server](https://www.wampserver.com/) (or XAMPP/LAMP)
- PHP 7.4+
- MySQL
- Composer (optional, for dependency management)

### Installation
1. **Clone or Download the Repository**
   - Place the project folder (`Fitness tracker website`) in your WAMP `www` directory (e.g., `C:/wamp64/www/`).

2. **Database Setup**
   - Start WAMP and open phpMyAdmin.
   - Create a new database named `fittrack`.
   - Import or create the required tables:
     - `users`, `user_profiles`, `bmi_records`, `workout_history`, `exercises`, `exercise_categories`, `nutrition_logs`, etc.
   - Example table creation scripts are available in the project or can be provided on request.

3. **Configure Database Connection**
   - Edit `includes/db_connect.php` if your MySQL username/password differs from the default (`root`/no password).

4. **Start the Server**
   - Launch WAMP and ensure Apache and MySQL are running.
   - Visit `http://localhost/Fitness%20tracker%20website/` in your browser.

5. **(Optional) Add Exercise Images/Videos**
   - Place exercise images in the database or use the default URLs provided in the code.
   - For video demos, use the `videos/` directory and update the code as needed.

## Project Structure
- `index.php` - Home page
- `dashboard.php` - User dashboard
- `bmi-calculator.php` - BMI calculator
- `nutrition.php` - Nutrition log
- `exercises.php` - Exercise library
- `dietary-meal-plans.php` - Meal plans
- `fitness-tips.php` - Fitness tips
- `faq.php` - Frequently asked questions
- `includes/` - Shared PHP includes (header, footer, db connection, functions)
- `css/` - Stylesheets
- `js/` - JavaScript files

## Customization
- Add or edit exercises and categories in the database or code.
- Update meal plans and tips in their respective PHP files.
- Adjust styles in the `css/` directory.

## License
This project is for educational and personal use. For commercial use, please ensure you have the rights to all images, videos, and third-party resources used.

---
For questions or support, open an issue or contact the project maintainer.
