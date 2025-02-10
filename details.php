<?php
session_start();

if (isset($_SESSION['timeout'])) {
    if (time() > $_SESSION['timeout']) {
        session_unset();
        session_destroy();
        header('Location: login.php');
        exit;
    } else {
        $_SESSION['timeout'] = time() + (5 * 60);
    }
} else {
    $_SESSION['timeout'] = time() + (5 * 60);
}

include('db.php');

if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

// Fetch user details
$user_id = $_SESSION['user_id'];
$details_query = "SELECT * FROM details WHERE user_id = '$user_id'";
$details_result = mysqli_query($mysqli, $details_query);
$details = mysqli_fetch_assoc($details_result);

// Fetch class and section
$class_query = "SELECT * FROM user_class uc JOIN class c ON uc.class_id = c.class_id WHERE uc.user_id = '$user_id'";
$class_result = mysqli_query($mysqli, $class_query);
$class = mysqli_fetch_assoc($class_result);

// Fetch all available subjects for selection
$all_subjects_query = "SELECT subject_id, subject_name FROM subjects";
$all_subjects_result = mysqli_query($mysqli, $all_subjects_query);
$all_subjects = [];
while ($row = mysqli_fetch_assoc($all_subjects_result)) {
    $all_subjects[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Details</title>
</head>

<body>
    <nav>
        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
            <a href="index.php">Home</a>
            <a href="details.php">Details</a>
            <a href="subjects.php">Subjects</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="index.php">Home</a>
            <a href="login.php">Login</a>
            <a href="signup.php">Sign Up</a>
        <?php endif; ?>
    </nav>

    <h1>Student Details</h1>
    <form method="POST" action="server2.php">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?= $details['name'] ?? '' ?>" required><br><br>

        <label for="roll_no">Roll No:</label>
        <input type="text" id="roll_no" name="roll_no" value="<?= $details['roll_no'] ?? '' ?>" required><br><br>
        
        <label for="class_name">Class:</label>
        <input type="text" id="class_name" name="class_name" value="<?= $class['class_name'] ?? '' ?>" required><br><br>

        <label for="section">Section:</label>
        <input type="text" id="section" name="section" value="<?= $class['section'] ?? '' ?>" required><br><br>

        <label>Subjects:</label><br>
        <?php foreach ($all_subjects as $subject): ?>
            <input type="checkbox" name="subjects[]" value="<?= $subject['subject_id'] ?>"
                <?= in_array($subject['subject_id'], array_column($subjects, 'subject_id')) ? 'checked' : '' ?>> <?= $subject['subject_name'] ?><br>
        <?php endforeach; ?>
        <br>

        <button type="submit">Update</button>
    </form>
</body>

</html>