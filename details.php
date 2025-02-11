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

// Query to fetch all classes
$class_query = "SELECT class_id, class_name, section FROM class";
$class_result = mysqli_query($mysqli, $class_query); // Execute the query
$classes = []; // Initialize an array to hold class data
while ($row = mysqli_fetch_assoc($class_result)) {
    $classes[] = $row; // Add each class to the classes array
}

// Query to fetch the user's class
$user_class_query = "SELECT class_id FROM user_class WHERE user_id = '$user_id'";
$user_class_result = mysqli_query($mysqli, $user_class_query); // Execute the query
$user_class = mysqli_fetch_assoc($user_class_result)['class_id'] ?? null; // Get the user's class ID

// Query to fetch all subjects
$all_subjects_query = "SELECT subject_id, subject_name FROM subjects";
$all_subjects_result = mysqli_query($mysqli, $all_subjects_query); // Execute the query
$all_subjects = []; // Initialize an array to hold subject data
while ($row = mysqli_fetch_assoc($all_subjects_result)) {
    $all_subjects[] = $row; // Add each subject to the all_subjects array
}

// Query to fetch the user's subjects
$user_subjects_query = "SELECT subject_id FROM user_subjects WHERE user_id = '$user_id'";
$user_subjects_result = mysqli_query($mysqli, $user_subjects_query); // Execute the query
$user_subjects = []; // Initialize an array to hold user's subject IDs
while ($row = mysqli_fetch_assoc($user_subjects_result)) {
    $user_subjects[] = $row['subject_id']; // Add each subject ID to the user_subjects array
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>details</title>
</head>
<body>
    <nav>
        <a href="index.php">home</a>
        <a href="details.php">details</a>
        <a href="subjects.php">subjects</a>
        <a href="logout.php">logout</a>
    </nav>

    <p>timeout in: <?= ($_SESSION['timeout'] - time()) / 60 ?> minutes</p> <!-- Display remaining session time -->

    <h1>update your details</h1>
    <form method="POST" action="server2.php"> <!-- Form to update user details -->
        <label for="name">name:</label>
        <input type="text" id="name" name="name" value="<?= $details['name'] ?? '' ?>" required><br><br>

        <label for="roll_no">roll no:</label>
        <input type="text" id="roll_no" name="roll_no" value="<?= $details['roll_no'] ?? '' ?>" required><br><br>

        <label>class:</label>
        <?php foreach ($classes as $class) : ?>
            <input type="radio" name="class_id" value="<?= $class['class_id'] ?>"
                <?= $class['class_id'] == $user_class ? 'checked' : '' ?>> <!-- Check if this class is the user's class -->
            <?= $class['class_name'] . ' - ' . $class['section'] ?><br>
        <?php endforeach; ?>
        <br>

        <label>subjects:</label><br>
        <?php foreach ($all_subjects as $subject) : ?>
            <input type="checkbox" name="subjects[]" value="<?= $subject['subject_id'] ?>"
                <?= in_array($subject['subject_id'], $user_subjects) ? 'checked' : '' ?>> <!-- Check if this subject is selected by the user -->
            <?= $subject['subject_name'] ?><br>
        <?php endforeach; ?>
        <br>

        <button type="submit" name="action" value="update">update</button> <!-- Button to update details -->
        <button type="submit" name="action" value="delete">delete</button> <!-- Button to delete user -->
    </form>
</body>
</html>