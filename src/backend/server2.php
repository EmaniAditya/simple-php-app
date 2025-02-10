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
    $class_name = mysqli_real_escape_string($mysqli, $_POST['class']);
    $section = mysqli_real_escape_string($mysqli, $_POST['section']);
    $selected_subjects = $_POST['subjects'] ?? [];

    // Update details
    $query = "INSERT INTO details (user, name, roll) VALUES ('$user_id', '$name', '$roll')
              ON DUPLICATE KEY UPDATE name='$name', roll='$roll'";
    mysqli_query($mysqli, $query);

    // Update class
    $class_query = "INSERT INTO class (user_id, class_name, section) VALUES ('$user_id', '$class_name', '$section')
                    ON DUPLICATE KEY UPDATE class_name='$class_name', section='$section'";
    mysqli_query($mysqli, $class_query);

    // Update user-subjects relationship
    $delete_subjects_query = "DELETE FROM user_subjects WHERE user_id = '$user_id'";
    mysqli_query($mysqli, $delete_subjects_query);

    foreach ($selected_subjects as $subject_id) {
        $insert_subject_query = "INSERT INTO user_subjects (user_id, subject_id) VALUES ('$user_id', '$subject_id')";
        mysqli_query($mysqli, $insert_subject_query);
    }

    $_SESSION['message'] = "details saved successfully!";
}

header('Location: exams.php');
exit;
