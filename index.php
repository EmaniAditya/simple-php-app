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

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

include('db.php');

$user_id = $_SESSION['user_id'];

$details_query = "SELECT d.name, d.roll_no, c.class_name, c.section
                  FROM details d
                  LEFT JOIN user_class uc ON d.user_id = uc.user_id
                  LEFT JOIN class c ON uc.class_id = c.class_id
                  WHERE d.user_id = '$user_id'";
$details_result = mysqli_query($mysqli, $details_query);
$details = mysqli_fetch_assoc($details_result);

$subjects_query = "SELECT s.subject_name
                   FROM subjects s
                   JOIN user_subjects us ON s.subject_id = us.subject_id
                   WHERE us.user_id = '$user_id'";
$subjects_result = mysqli_query($mysqli, $subjects_query);
$subjects = [];
while ($row = mysqli_fetch_assoc($subjects_result)) {
    $subjects[] = $row['subject_name'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
    <nav>
        <a href="index.php">Home</a>
        <a href="details.php">Details</a>
        <a href="subjects.php">Subjects</a>
        <a href="logout.php">Logout</a>
    </nav>

    <p>Timeout in: <?= ($_SESSION['timeout'] - time()) / 60 ?> minutes</p>

    <h1>Welcome, <?= $_SESSION['username'] ?>!</h1>

    <h2>Your Details</h2>
    <?php if ($details) : ?>
        <table border="1">
            <tr>
                <th>Name</th>
                <td><?= $details['name'] ?></td>
            </tr>
            <tr>
                <th>Roll No</th>
                <td><?= $details['roll_no'] ?></td>
            </tr>
            <tr>
                <th>Class</th>
                <td><?= $details['class_name'] ?></td>
            </tr>
            <tr>
                <th>Section</th>
                <td><?= $details['section'] ?></td>
            </tr>
            <tr>
                <th>Subjects</th>
                <td><?= implode(", ", $subjects) ?></td>
            </tr>
        </table>
    <?php else : ?>
        <p>No details found. Please <a href="details.php">add your details</a>.</p>
    <?php endif; ?>
</body>
</html>
