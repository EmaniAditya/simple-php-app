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

$query = "SELECT subject_id, subject_name FROM subjects";
$result = mysqli_query($mysqli, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Subjects</title>
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

    <p>timeout in: <?= ($_SESSION['timeout'] - time()) / 60 ?> minutes</p>

    <h1>Subjects and Exam Dates</h1>
    <table border="1">
        <tr>
            <th>Subject ID</th>
            <th>Subject Name</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td><?= $row['subject_id'] ?></td>
                <td><?= $row['subject_name'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>

</html>