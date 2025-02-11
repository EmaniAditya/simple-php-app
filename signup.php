<?php
session_start(); // Start a new session or resume the existing session

// Check if the user is already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: index.php'); // Redirect to the index page if logged in
    exit; // Stop further script execution
}

// Check if there is a message to display (e.g., error or success messages)
if (isset($_SESSION['message'])) {
    echo "<p>" . $_SESSION['message'] . "</p>"; // Display the message
    unset($_SESSION['message']); // Clear the message from the session
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>signup</title> <!-- Title of the signup page -->
</head>

<body>
    <h2>signup</h2> <!-- Header for the signup form -->
    <form method="POST" action="server.php"> <!-- Form submission to server.php -->
        <input type="hidden" name="action" value="signup"> <!-- Hidden field to specify action -->
        <label for="username">username:</label> <!-- Label for username input -->
        <input type="text" id="username" name="username" required><br><br> <!-- Username input field -->

        <label for="password">password:</label> <!-- Label for password input -->
        <input type="password" id="password" name="password" required><br><br> <!-- Password input field -->

        <button type="submit">signup</button> <!-- Submit button for the form -->
    </form>
    <p><a href="login.php">login here</a></p> <!-- Link to the login page -->
</body>

</html>