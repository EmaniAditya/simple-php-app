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

// Fetch class and section
$class_query = "SELECT * FROM class WHERE user_id = '$user_id'";
$class_result = mysqli_query($mysqli, $class_query);
$class = mysqli_fetch_assoc($class_result);

// Fetch subjects related to the user
$subjects_query = "SELECT s.subject_id, s.subject_name 
                   FROM subjects s 
                   JOIN user_subjects us ON s.subject_id = us.subject_id 
                   WHERE us.user_id = '$user_id'";
$subjects_result = mysqli_query($mysqli, $subjects_query);
$subjects = [];
while ($row = mysqli_fetch_assoc($subjects_result)) {
    $subjects[] = $row;
}

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
    <title>details</title>
</head>

<body>
    <nav>
        <a href="index.php">home</a>
        <a href="logout.php">logout</a>
    </nav>

    <h1>Student details</h1>
    <form method="POST" action="server2.php">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?= $details['name'] ?>" required><br><br>

        <label for="roll">Roll No:</label>
        <input type="text" id="roll" name="roll" value="<?= $details['roll'] ?>" required><br><br>

        <label for="class">Class:</label>
        <input type="text" id="class" name="class" value="<?= $class['class_name'] ?>" required><br><br>

        <label for="section">Section:</label>
        <input type="text" id="section" name="section" value="<?= $class['section'] ?>" required><br><br>

        <label for="subjects">Subjects:</label><br>
        <?php foreach ($all_subjects as $subject) : ?>
            <input type="checkbox" name="subjects[]" value="<?= $subject['subject_id'] ?>"
                <?= in_array($subject['subject_id'], array_column($subjects, 'subject_id')) ? 'checked' : '' ?>> <?= $subject['subject_name'] ?><br>
        <?php endforeach; ?>
        <br>

        <button type="submit">Update</button>
    </form>

    <form method="POST" action="delete_details.php">
        <input type="hidden" name="user_id" value="<?= $user_id ?>">
        <button type="submit">Delete</button>
    </form>
</body>

</html>