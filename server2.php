<?php
session_start(); // Start the session to access session variables
include('db.php'); // Include the database connection file

// Check if the user is logged in; if not, redirect to the login page
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'update'; // Determine the action (default is 'update')
    $user_id = $_SESSION['user_id']; // Get the logged-in user's ID

    // If the action is 'delete', remove user details from the database
    if ($action === 'delete') {
        // Delete user details from the 'details' table
        mysqli_query($mysqli, "DELETE FROM details WHERE user_id = '$user_id'");
        // Delete user class associations from the 'user_class' table
        mysqli_query($mysqli, "DELETE FROM user_class WHERE user_id = '$user_id'");
        // Delete user subjects from the 'user_subjects' table
        mysqli_query($mysqli, "DELETE FROM user_subjects WHERE user_id = '$user_id'");

        $_SESSION['message'] = "details deleted successfully"; // Set success message
        header('Location: index.php'); // Redirect to the index page
        exit;
    } else {
        // If the action is 'update', process the form data
        $name = mysqli_real_escape_string($mysqli, $_POST['name']); // Sanitize user input
        $roll_no = mysqli_real_escape_string($mysqli, $_POST['roll_no']); // Sanitize user input
        $class_id = mysqli_real_escape_string($mysqli, $_POST['class_id']); // Sanitize user input
        $selected_subjects = $_POST['subjects'] ?? []; // Get selected subjects

        // Insert or update user details in the 'details' table
        mysqli_query($mysqli, "INSERT INTO details (user_id, name, roll_no)
                              VALUES ('$user_id', '$name', '$roll_no')
                              ON DUPLICATE KEY UPDATE
                              name = VALUES(name),
                              roll_no = VALUES(roll_no)");

        // Remove existing class associations for the user
        mysqli_query($mysqli, "DELETE FROM user_class WHERE user_id = '$user_id'");
        // Insert the new class association for the user
        mysqli_query($mysqli, "INSERT INTO user_class (user_id, class_id) VALUES ('$user_id', '$class_id')");

        // Remove existing subject associations for the user
        mysqli_query($mysqli, "DELETE FROM user_subjects WHERE user_id = '$user_id'");
        // Insert new subject associations for the user
        foreach ($selected_subjects as $subject_id) {
            $subject_id = mysqli_real_escape_string($mysqli, $subject_id); // Sanitize subject ID
            mysqli_query($mysqli, "INSERT INTO user_subjects (user_id, subject_id) VALUES ('$user_id', '$subject_id')");
        }

        $_SESSION['message'] = "details updated successfully"; // Set success message
        header('Location: index.php'); // Redirect to the index page
        exit;
    }
}
