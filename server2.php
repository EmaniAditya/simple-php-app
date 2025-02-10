<?php
session_start();
include('db.php');

if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $name = mysqli_real_escape_string($mysqli, $_POST['name']);
    $roll = mysqli_real_escape_string($mysqli, $_POST['roll']);
    $subject_id = mysqli_real_escape_string($mysqli, $_POST['subject']);

    $query = "INSERT INTO details (user, name, roll, subject) VALUES ('$user_id', '$name', '$roll', '$subject_id')
              ON DUPLICATE KEY UPDATE name='$name', roll='$roll', subject='$subject_id'";

    if (mysqli_query($mysqli, $query)) {
        $_SESSION['message'] = "details saved successfully!";
    } else {
        $_SESSION['message'] = "Error saving details: " . mysqli_error($mysqli);
    }
}

header('Location: exams.php');
exit;
