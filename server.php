<?php
session_start(); // Start the session to manage user login state
include('db.php'); // Include database connection file

// Check if the user is already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: index.php'); // Redirect to index if logged in
    exit; // Stop further execution
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        // User signup process
        if ($_POST['action'] === 'signup') {
            $username = mysqli_real_escape_string($mysqli, $_POST['username']); // Sanitize username input
            $password = mysqli_real_escape_string($mysqli, $_POST['password']); // Sanitize password input

            // Check if the username already exists
            $check_query = "SELECT id FROM users WHERE username = '$username'";
            $result = mysqli_query($mysqli, $check_query);

            // If username exists, redirect to signup with a message
            if (mysqli_num_rows($result) > 0) {
                $_SESSION['message'] = "account exists"; // Set message for existing account
                header('Location: signup.php'); // Redirect to signup page
                exit; // Stop further execution
            }

            // Insert new user into the database
            $query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";

            // Check if the insertion was successful
            if (mysqli_query($mysqli, $query)) {
                $_SESSION['message'] = "signup done, now login"; // Set success message
                header('Location: login.php'); // Redirect to login page
            } else {
                $_SESSION['message'] = "signup failed!"; // Set failure message
                header('Location: signup.php'); // Redirect back to signup page
            }
            // User login process
        } elseif ($_POST['action'] === 'login') {
            $username = mysqli_real_escape_string($mysqli, $_POST['username']); // Sanitize username input
            $password = mysqli_real_escape_string($mysqli, $_POST['password']); // Sanitize password input

            // Query to check user credentials
            $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
            $result = mysqli_query($mysqli, $query);

            // If credentials are valid, log the user in
            if (mysqli_num_rows($result) === 1) {
                $user = mysqli_fetch_assoc($result); // Fetch user data
                $_SESSION['loggedin'] = true; // Set logged in status
                $_SESSION['user_id'] = $user['id']; // Store user ID in session
                $_SESSION['username'] = $user['username']; // Store username in session
                $_SESSION['timeout'] = time() + (5 * 60); // Set session timeout for 5 minutes
                header('Location: index.php'); // Redirect to index page
            } else {
                $_SESSION['message'] = "invalid details"; // Set message for invalid login
                header('Location: login.php'); // Redirect to login page
            }
        }
    }
}
mysqli_close($mysqli); // Close the database connection
