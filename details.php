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
        <label for="name">name:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="roll">roll no:</label>
        <input type="text" id="roll" name="roll" required><br><br>

        <label for="subject">subject:</label>
        <select id="subject" name="subject" required>
            <option value="">select subject</option>
            <?php while ($row = mysqli_fetch_assoc($subjects_result)) : ?>
                <option value="<?= $row['subject_id'] ?>"><?= $row['subject_name'] ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <button type="submit">save</button>
    </form>
</body>

</html>