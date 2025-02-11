<?php
session_start();
include('db.php');

if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'update';
    $user_id = $_SESSION['user_id'];

    if ($action === 'delete') {
        mysqli_query($mysqli, "DELETE FROM details WHERE user_id = '$user_id'");
        mysqli_query($mysqli, "DELETE FROM user_class WHERE user_id = '$user_id'");
        mysqli_query($mysqli, "DELETE FROM user_subjects WHERE user_id = '$user_id'");

        $_SESSION['message'] = "details deleted successfully";
        header('Location: index.php');
        exit;
    } else {
        $name = mysqli_real_escape_string($mysqli, $_POST['name']);
        $roll_no = mysqli_real_escape_string($mysqli, $_POST['roll_no']);
        $class_id = mysqli_real_escape_string($mysqli, $_POST['class_id']);
        $selected_subjects = $_POST['subjects'] ?? [];

        mysqli_query($mysqli, "INSERT INTO details (user_id, name, roll_no)
                              VALUES ('$user_id', '$name', '$roll_no')
                              ON DUPLICATE KEY UPDATE
                              name = VALUES(name),
                              roll_no = VALUES(roll_no)");


        mysqli_query($mysqli, "DELETE FROM user_class WHERE user_id = '$user_id'");
        mysqli_query($mysqli, "INSERT INTO user_class (user_id, class_id) VALUES ('$user_id', '$class_id')");


        mysqli_query($mysqli, "DELETE FROM user_subjects WHERE user_id = '$user_id'");
        foreach ($selected_subjects as $subject_id) {
            $subject_id = mysqli_real_escape_string($mysqli, $subject_id);
            mysqli_query($mysqli, "INSERT INTO user_subjects (user_id, subject_id) VALUES ('$user_id', '$subject_id')");
        }

        $_SESSION['message'] = "details updated successfully";
        header('Location: index.php');
        exit;
    }
}
?>