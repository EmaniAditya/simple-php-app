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

$query = "SELECT subject_name, teacher_name, exam_date FROM subjects";
$result = mysqli_query($mysqli, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Subjects</title>
</head>

<body>
    <nav>
        <a href="index.php">home</a>
        <a href="logout.php">logout</a>
    </nav>

    <p>timeout in: <?= ($_SESSION['timeout'] - time()) / 60 ?> minutes</p>

    <h1>Subjects and Exam Dates</h1>
    <table border="1">
        <tr>
            <th>Subject</th>
            <th>Teacher</th>
            <th>Exam Date</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td><?= $row['subject_name'] ?></td>
                <td><?= $row['teacher_name'] ?></td>
                <td><?= $row['exam_date'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>

</html>