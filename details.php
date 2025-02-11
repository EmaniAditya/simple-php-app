<?php
session_start();

// Check for session timeout
if (isset($_SESSION['timeout'])) {
    if (time() > $_SESSION['timeout']) {
        session_unset();
        session_destroy();
        header('Location: login.php');
        exit;
    } else {
        $_SESSION['timeout'] = time() + (5 * 60); // Reset timeout
    }
} else {
    $_SESSION['timeout'] = time() + (5 * 60); // Set initial timeout
}

// Redirect if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

include('db.php');

// Fetch user details
$user_id = $_SESSION['user_id'];
$details_query = "SELECT d.*, c.class_name, c.section 
                  FROM details d 
                  LEFT JOIN user_class uc ON d.user_id = uc.user_id 
                  LEFT JOIN class c ON uc.class_id = c.class_id 
                  WHERE d.user_id = '$user_id'";
$details_result = mysqli_query($mysqli, $details_query);
$details = mysqli_fetch_assoc($details_result);

// Fetch all available subjects
$all_subjects_query = "SELECT subject_id, subject_name FROM subjects";
$all_subjects_result = mysqli_query($mysqli, $all_subjects_query);
$all_subjects = [];
while ($row = mysqli_fetch_assoc($all_subjects_result)) {
    $all_subjects[] = $row;
}

// Fetch user's selected subjects
$user_subjects_query = "SELECT subject_id FROM user_subjects WHERE user_id = '$user_id'";
$user_subjects_result = mysqli_query($mysqli, $user_subjects_query);
$user_subjects = [];
while ($row = mysqli_fetch_assoc($user_subjects_result)) {
    $user_subjects[] = $row['subject_id'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Details</title>
</head>
<body>
    <!-- Navigation Links -->
    <nav>
        <a href="index.php">Home</a>
        <a href="details.php">Details</a>
        <a href="subjects.php">Subjects</a>
        <a href="logout.php">Logout</a>
    </nav>

    <!-- Timeout Message -->
    <p>Timeout in: <?= ($_SESSION['timeout'] - time()) / 60 ?> minutes</p>

    <!-- Form to Update/Delete Details -->
    <h1>Update Your Details</h1>
    <form method="POST" action="server2.php">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?= $details['name'] ?? '' ?>" required><br><br>

        <label for="roll_no">Roll No:</label>
        <input type="text" id="roll_no" name="roll_no" value="<?= $details['roll_no'] ?? '' ?>" required><br><br>

        <label for="class_name">Class:</label>
        <input type="text" id="class_name" name="class_name" value="<?= $details['class_name'] ?? '' ?>" required><br><br>

        <label for="section">Section:</label>
        <input type="text" id="section" name="section" value="<?= $details['section'] ?? '' ?>" required><br><br>

        <label>Subjects:</label><br>
        <?php foreach ($all_subjects as $subject) : ?>
            <input type="checkbox" name="subjects[]" value="<?= $subject['subject_id'] ?>"
                <?= in_array($subject['subject_id'], $user_subjects) ? 'checked' : '' ?>>
            <?= $subject['subject_name'] ?><br>
        <?php endforeach; ?>
        <br>

        <button type="submit" name="action" value="update">Update</button>
        <button type="submit" name="action" value="delete">Delete</button>
    </form>
</body>
</html>