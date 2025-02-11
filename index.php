<?php
session_start(); // Start the session to manage user login state

// Check if session timeout is set
if (isset($_SESSION['timeout'])) {
    // If the current time exceeds the timeout, destroy the session and redirect to login
    if (time() > $_SESSION['timeout']) {
        session_unset(); // Clear session variables
        session_destroy(); // Destroy the session
        header('Location: login.php'); // Redirect to login page
        exit; // Stop further execution
    } else {
        // Reset the timeout for the session
        $_SESSION['timeout'] = time() + (5 * 60); // Extend timeout by 5 minutes
    }
} else {
    // Initialize session timeout if not set
    $_SESSION['timeout'] = time() + (5 * 60); // Set timeout for 5 minutes
}

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit; // Stop further execution
}

include('db.php'); // Include database connection file

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Query to fetch user details from the database
$details_query = "SELECT * FROM details WHERE user_id = '$user_id'";
$details_result = mysqli_query($mysqli, $details_query); // Execute the query
$details = mysqli_fetch_assoc($details_result); // Fetch user details

// Query to fetch the user's class ID
$class_id_query = "SELECT class_id FROM user_class WHERE user_id = '$user_id'";
$class_id_result = mysqli_query($mysqli, $class_id_query); // Execute the query
$class_id_row = mysqli_fetch_assoc($class_id_result); // Fetch the class ID
$class_id = $class_id_row['class_id']; // Store the class ID

// Query to fetch class details based on class ID
$class_query = "SELECT class_name, section FROM class WHERE class_id = '$class_id'";
$class_result = mysqli_query($mysqli, $class_query); // Execute the query
$class = mysqli_fetch_assoc($class_result); // Fetch class details

// Query to fetch all subject IDs for the user
$subject_ids_query = "SELECT subject_id FROM user_subjects WHERE user_id = '$user_id'";
$subject_ids_result = mysqli_query($mysqli, $subject_ids_query); // Execute the query
$subject_ids = []; // Initialize an array to hold subject IDs
while ($row = mysqli_fetch_assoc($subject_ids_result)) {
    $subject_ids[] = $row['subject_id']; // Add each subject ID to the subject_ids array
}

// Query to fetch subject names based on subject IDs
$subjects = []; // Initialize an array to hold subject names
foreach ($subject_ids as $subject_id) {
    $subject_query = "SELECT subject_name FROM subjects WHERE subject_id = '$subject_id'";
    $subject_result = mysqli_query($mysqli, $subject_query); // Execute the query
    $subject_row = mysqli_fetch_assoc($subject_result); // Fetch subject details
    $subjects[] = $subject_row['subject_name']; // Add subject name to the subjects array
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home</title>
</head>

<body>
    <nav>
        <a href="index.php">home</a>
        <a href="details.php">details</a>
        <a href="subjects.php">subjects</a>
        <a href="logout.php">logout</a>
    </nav>

    <p>timeout in: <?= ($_SESSION['timeout'] - time()) / 60 ?> minutes</p> <!-- Display remaining session time -->

    <h1>welcome, <?= $_SESSION['username'] ?>!</h1> <!-- Display welcome message with username -->

    <h2>ur details</h2>
    <?php if ($details) : ?>
        <table border="1">
            <tr>
                <th>name</th>
                <td><?= $details['name'] ?></td> <!-- Display user's name -->
            </tr>
            <tr>
                <th>roll no</th>
                <td><?= $details['roll_no'] ?></td> <!-- Display user's roll number -->
            </tr>
        </table>
    <?php else : ?>
        <p>no details found. please <a href="details.php">add your details</a>.</p> <!-- Prompt to add details if none found -->
    <?php endif; ?>

    <h2>ur class</h2>
    <?php if ($class) : ?>
        <table border="1">
            <tr>
                <th>class</th>
                <td><?= $class['class_name'] ?></td> <!-- Display class name -->
            </tr>
            <tr>
                <th>section</th>
                <td><?= $class['section'] ?></td> <!-- Display class section -->
            </tr>
        </table>
    <?php else : ?>
        <p>no class information found.</p> <!-- Message if no class information is available -->
    <?php endif; ?>

    <h2>ur subjects</h2>
    <?php if (!empty($subjects)) : ?>
        <table border="1">
            <tr>
                <th>subjects</th>
            </tr>
            <?php foreach ($subjects as $subject) : ?>
                <tr>
                    <td><?= $subject ?></td> <!-- Display each subject name -->
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else : ?>
        <p>no subjects found.</p> <!-- Message if no subjects are available -->
    <?php endif; ?>
</body>

</html>