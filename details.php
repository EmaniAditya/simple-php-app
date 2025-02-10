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

$subjects_query = "SELECT subject_id, subject_name FROM subjects";
$subjects_result = mysqli_query($mysqli, $subjects_query);
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

    <p>timeout in: <?= ($_SESSION['timeout'] - time()) / 60 ?> minutes</p>

    <h1>Student details</h1>
    <form method="POST" action="server2.php">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="roll_no">Roll No:</label>
        <input type="text" id="roll_no" name="roll_no" required><br><br>

        <label for="class_name">Class:</label>
        <input type="text" id="class_name" name="class_name" required>

        <label for="section">Section:</label>
        <input type="text" id="section" name="section" required><br><br>

        <label>Subjects:</label><br>
        <?php 
        $subjects = mysqli_query($mysqli, "SELECT * FROM subjects");
        while($subject = mysqli_fetch_assoc($subjects)): ?>
            <input type="checkbox" name="subjects[]" value="<?= $subject['subject_id'] ?>">
            <?= $subject['subject_name'] ?><br>
        <?php endwhile; ?>

        <button type="submit">Save</button>
    </form>
</body>

</html>