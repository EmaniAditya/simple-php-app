# SimpleApp

SimpleApp is a basic web application built with PHP and MySQL, designed to manage user details, classes, and subjects. This project is intended for learning purposes, demonstrating fundamental CRUD operations and session management.

## Features

- **User Authentication**: Sign up and log in functionality with session management.
- **User Details**: View, update, and delete personal details.
- **Class and Subject Management**: Assign and manage classes and subjects for users.
- **Session Timeout**: Automatic logout after a period of inactivity.

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

## Usage

1. **Setup**: Ensure you have a running web server with PHP and MySQL.
2. **Database**: Import the database schema provided in the project.
3. **Configuration**: Update `db.php` with your database credentials.
4. **Run**: Access the application via a web browser.

## Notes

- This project is designed for educational purposes to demonstrate basic web application concepts.
- The SQL queries are kept simple for clarity and ease of understanding.

