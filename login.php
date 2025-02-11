<?php
session_start(); // Start the session to access session variables

// Check if the user is already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: index.php'); // Redirect to index.php if logged in
    exit; // Stop further execution
}

// Check if there is a message to display (e.g., login errors)
if (isset($_SESSION['message'])) {
    echo "<p>" . $_SESSION['message'] . "</p>"; // Display the message
    unset($_SESSION['message']); // Clear the message after displaying
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>login</title> <!-- Title of the login page -->
</head>

<body>
    <h2>login</h2> <!-- Heading for the login form -->
    <form method="POST" action="server.php"> <!-- Form submission to server.php -->
        <input type="hidden" name="action" value="login"> <!-- Hidden field to specify action -->
        <label for="username">username:</label>
        <input type="text" id="username" name="username" required><br><br> <!-- Username input -->

        <label for="password">password:</label>
        <input type="password" id="password" name="password" required><br><br> <!-- Password input -->

        <button type="submit">login</button> <!-- Submit button for the form -->
    </form>
    <p><a href="signup.php">signup here</a></p> <!-- Link to the signup page -->
</body>

</html>