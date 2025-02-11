<?php
session_start(); // Start the session to manage user login state

// Check if the session timeout is set
if (isset($_SESSION['timeout'])) {
    // If the current time exceeds the timeout, destroy the session
    if (time() > $_SESSION['timeout']) {
        session_unset(); // Clear session variables
        session_destroy(); // Destroy the session
        header('Location: login.php'); // Redirect to login page
        exit; // Stop further execution
    } else {
        // Reset the timeout to 5 minutes from now
        $_SESSION['timeout'] = time() + (5 * 60);
    }
} else {
    // Initialize the timeout for the first time
    $_SESSION['timeout'] = time() + (5 * 60);
}

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit; // Stop further execution
}

// Include database connection file
include('db.php');

// Query to fetch subject details from the database
$query = "SELECT subject_id, subject_name FROM subjects";
$result = mysqli_query($mysqli, $query); // Execute the query
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>subjects</title> <!-- Page title -->
</head>

<body>
    <nav>
        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
            <!-- Navigation links for logged-in users -->
            <a href="index.php">home</a>
            <a href="details.php">details</a>
            <a href="subjects.php">subjects</a>
            <a href="logout.php">logout</a>
        <?php else: ?>
            <!-- Navigation links for guests -->
            <a href="index.php">home</a>
            <a href="login.php">login</a>
            <a href="signup.php">sign Up</a>
        <?php endif; ?>
    </nav>

    <p>timeout in: <?= ($_SESSION['timeout'] - time()) / 60 ?> minutes</p> <!-- Display remaining session time -->

    <h1>subjects</h1>
    <table border="1">
        <tr>
            <th>subject id</th>
            <th>subject name</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td><?= $row['subject_id'] ?></td> <!-- Display subject ID -->
                <td><?= $row['subject_name'] ?></td> <!-- Display subject name -->
            </tr>
        <?php endwhile; ?>
    </table>
</body>

</html>