<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: index.php');
    exit;
}

if (isset($_SESSION['message'])) {
    echo "<p>" . $_SESSION['message'] . "</p>";
    unset($_SESSION['message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>signup</title>
</head>
<body>
    <h2>signup</h2>
    <form method="POST" action="server.php">
        <input type="hidden" name="action" value="signup">
        <label for="username">username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">signup</button>
    </form>
    <p><a href="login.php">login here</a></p>
</body>
</html>