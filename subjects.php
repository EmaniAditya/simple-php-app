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
    <title>subjects</title>
</head>

<body>
    <nav>
        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
            <a href="index.php">home</a>
            <a href="details.php">details</a>
            <a href="subjects.php">subjects</a>
            <a href="logout.php">logout</a>
        <?php else: ?>
            <a href="index.php">home</a>
            <a href="login.php">login</a>
            <a href="signup.php">sign Up</a>
        <?php endif; ?>
    </nav>

    <p>timeout in: <?= ($_SESSION['timeout'] - time()) / 60 ?> minutes</p>

    <h1>subjects</h1>
    <table border="1">
        <tr>
            <th>subject id</th>
            <th>subject name</th>
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