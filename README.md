# simple-php-app

simple-php-app is a basic web application built with PHP and MySQL, designed to manage user details, classes, and subjects. This project is intended for learning purposes, demonstrating fundamental CRUD operations and session management.

## Features

- **User Authentication**: Sign up and log in functionality with session management.
- **User Details**: View, update, and delete personal details.
- **Class and Subject Management**: Assign and manage classes and subjects for users.
- **Session Timeout**: Automatic logout after a period of inactivity.

## Quick Setup Guide

1. **Install a Local Server**:

   - Download and install XAMPP.
   - Start the server.

2. **Set Up the Database**:

   - Open phpMyAdmin (usually at `http://localhost/phpmyadmin`).
   - Create a new database named `loginDB`.
   - Import the provided SQL schema file into the database.

3. **Configure Database Connection**:

   - Open `db.php` and update the database credentials if necessary.
     - `$host`: Usually `localhost`
     - `$dbname`: `loginDB`
     - `$username`: `root` (default)
     - `$password`: Leave empty for XAMPP/WAMP default

4. **Run the Application**:

   - Place the project folder in the `htdocs` directory (for XAMPP).
   - Access the application at `http://localhost/your_project_folder/index.php`.

5. **Create an User**:
   - Use the signup page to create a new user account.
   - Log in with the created account to access the application features.

## Notes

- This project is designed for educational purposes to demonstrate basic web application concepts.
- The SQL queries are kept simple for clarity and ease of understanding.

## File Structure

- `db.php`: Database connection setup.
- `index.php`: Home page displaying user details.
- `details.php`: Form to update or delete user details.
- `login.php`: User login page.
- `signup.php`: User registration page.
- `logout.php`: Logout functionality.
- `server.php`: Handles login and signup logic.
- `server2.php`: Handles update and delete logic for user details.
- `subjects.php`: Displays available subjects.

## Database Structure

- **users**: Stores user credentials (`username`, `password`).
- **details**: Stores user personal details (`name`, `roll_no`).
- **class**: Stores class information (`class_name`, `section`).
- **subjects**: Stores subject information (`subject_name`).
- **user_class**: Maps users to their classes.
- **user_subjects**: Maps users to their subjects.
