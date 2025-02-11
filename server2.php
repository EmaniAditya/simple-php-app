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
        mysqli_begin_transaction($mysqli);
        try {
            mysqli_query($mysqli, "DELETE FROM details WHERE user_id = '$user_id'");
            mysqli_query($mysqli, "DELETE FROM user_class WHERE user_id = '$user_id'");
            mysqli_query($mysqli, "DELETE FROM user_subjects WHERE user_id = '$user_id'");
            mysqli_commit($mysqli);
            $_SESSION['message'] = "Details deleted successfully!";
        } catch (Exception $e) {
            mysqli_rollback($mysqli);
            $_SESSION['error'] = "Error deleting details: " . $e->getMessage();
        }
        header('Location: index.php');
        exit;
    } else {
        $name = mysqli_real_escape_string($mysqli, $_POST['name']);
        $roll_no = mysqli_real_escape_string($mysqli, $_POST['roll_no']);
        $class_id = mysqli_real_escape_string($mysqli, $_POST['class_id']);
        $selected_subjects = $_POST['subjects'] ?? [];

        mysqli_begin_transaction($mysqli);
        try {
            $details_query = "INSERT INTO details (user_id, name, roll_no)
                              VALUES ('$user_id', '$name', '$roll_no')
                              ON DUPLICATE KEY UPDATE
                              name = VALUES(name),
                              roll_no = VALUES(roll_no)";
            if (!mysqli_query($mysqli, $details_query)) {
                throw new Exception("Error updating details: " . mysqli_error($mysqli));
            }

            mysqli_query($mysqli, "DELETE FROM user_class WHERE user_id = '$user_id'");
            $link_class = "INSERT INTO user_class (user_id, class_id) VALUES ('$user_id', '$class_id')";
            if (!mysqli_query($mysqli, $link_class)) {
                throw new Exception("Error linking class: " . mysqli_error($mysqli));
            }

            mysqli_query($mysqli, "DELETE FROM user_subjects WHERE user_id = '$user_id'");
            foreach ($selected_subjects as $subject_id) {
                $subject_id = mysqli_real_escape_string($mysqli, $subject_id);
                $insert_subject = "INSERT INTO user_subjects (user_id, subject_id) VALUES ('$user_id', '$subject_id')";
                if (!mysqli_query($mysqli, $insert_subject)) {
                    throw new Exception("Error adding subject: " . mysqli_error($mysqli));
                }
            }

            mysqli_commit($mysqli);
            $_SESSION['message'] = "Details updated successfully!";
        } catch (Exception $e) {
            mysqli_rollback($mysqli);
            $_SESSION['error'] = "Error updating: " . $e->getMessage();
        }
        header('Location: index.php');
        exit;
    }
}
?>
