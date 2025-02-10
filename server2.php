if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $name = mysqli_real_escape_string($mysqli, $_POST['name']);
    $roll_no = mysqli_real_escape_string($mysqli, $_POST['roll_no']);
    $class_name = mysqli_real_escape_string($mysqli, $_POST['class_name']);
    $section = mysqli_real_escape_string($mysqli, $_POST['section']);
    $selected_subjects = $_POST['subjects'] ?? [];

    // Start transaction
    mysqli_begin_transaction($mysqli);

    try {
        // Insert/Update details
        $details_query = "INSERT INTO details (user_id, name, roll_no) 
                          VALUES ('$user_id', '$name', '$roll_no')
                          ON DUPLICATE KEY UPDATE 
                          name = VALUES(name), 
                          roll_no = VALUES(roll_no)";
        if (!mysqli_query($mysqli, $details_query)) {
            throw new Exception("Error inserting details: " . mysqli_error($mysqli));
        }

        // Insert/Update class
        $class_query = "INSERT INTO class (class_name, section) 
                        VALUES ('$class_name', '$section')";
        if (!mysqli_query($mysqli, $class_query)) {
            throw new Exception("Error inserting class: " . mysqli_error($mysqli));
        }
        $class_id = mysqli_insert_id($mysqli);

        // Link user to class
        $user_class_query = "INSERT INTO user_class (user_id, class_id) 
                             VALUES ('$user_id', '$class_id')
                             ON DUPLICATE KEY UPDATE class_id = VALUES(class_id)";
        if (!mysqli_query($mysqli, $user_class_query)) {
            throw new Exception("Error linking user to class: " . mysqli_error($mysqli));
        }

        // Update user subjects
        mysqli_query($mysqli, "DELETE FROM user_subjects WHERE user_id = '$user_id'");
        
        foreach ($selected_subjects as $subject_id) {
            $subject_id = mysqli_real_escape_string($mysqli, $subject_id);
            $user_subjects_query = "INSERT INTO user_subjects (user_id, subject_id) 
                                    VALUES ('$user_id', '$subject_id')";
            if (!mysqli_query($mysqli, $user_subjects_query)) {
                throw new Exception("Error inserting user subjects: " . mysqli_error($mysqli));
            }
        }

        mysqli_commit($mysqli);
        $_SESSION['message'] = "Details saved successfully!";
    } catch (Exception $e) {
        mysqli_rollback($mysqli);
        $_SESSION['error'] = "Error saving details: " . $e->getMessage();
    }
}