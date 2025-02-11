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

$details_query = "SELECT * FROM details WHERE user_id = '$user_id'";
$details_result = mysqli_query($mysqli, $details_query);
$details = mysqli_fetch_assoc($details_result);

$class_id_query = "SELECT class_id FROM user_class WHERE user_id = '$user_id'";
$class_id_result = mysqli_query($mysqli, $class_id_query);
$class_id_row = mysqli_fetch_assoc($class_id_result);
$class_id = $class_id_row['class_id'];

$class_query = "SELECT class_name, section FROM class WHERE class_id = '$class_id'";
$class_result = mysqli_query($mysqli, $class_query);
$class = mysqli_fetch_assoc($class_result);

$subject_ids_query = "SELECT subject_id FROM user_subjects WHERE user_id = '$user_id'";
$subject_ids_result = mysqli_query($mysqli, $subject_ids_query);
$subject_ids = [];
while ($row = mysqli_fetch_assoc($subject_ids_result)) {
    $subject_ids[] = $row['subject_id'];
}

$subjects = [];
foreach ($subject_ids as $subject_id) {
    $subject_query = "SELECT subject_name FROM subjects WHERE subject_id = '$subject_id'";
    $subject_result = mysqli_query($mysqli, $subject_query);
    $subject_row = mysqli_fetch_assoc($subject_result);
    $subjects[] = $subject_row['subject_name'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home</title>
</head>
<body>
    <nav>
        <a href="index.php">home</a>
        <a href="details.php">details</a>
        <a href="subjects.php">subjects</a>
        <a href="logout.php">logout</a>
    </nav>

    <p>timeout in: <?= ($_SESSION['timeout'] - time()) / 60 ?> minutes</p>

    <h1>welcome, <?= $_SESSION['username'] ?>!</h1>

    <h2>ur details</h2>
    <?php if ($details) : ?>
        <table border="1">
            <tr>
                <th>name</th>
                <td><?= $details['name'] ?></td>
            </tr>
            <tr>
                <th>roll no</th>
                <td><?= $details['roll_no'] ?></td>
            </tr>
        </table>
    <?php else : ?>
        <p>no details found. please <a href="details.php">add your details</a>.</p>
    <?php endif; ?>

    <h2>ur class</h2>
    <?php if ($class) : ?>
        <table border="1">
            <tr>
                <th>class</th>
                <td><?= $class['class_name'] ?></td>
            </tr>
            <tr>
                <th>section</th>
                <td><?= $class['section'] ?></td>
            </tr>
        </table>
    <?php else : ?>
        <p>no class information found.</p>
    <?php endif; ?>

    <h2>ur subjects</h2>
    <?php if (!empty($subjects)) : ?>
        <table border="1">
            <tr>
                <th>subjects</th>
            </tr>
            <?php foreach ($subjects as $subject) : ?>
                <tr>
                    <td><?= $subject ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else : ?>
        <p>no subjects found.</p>
    <?php endif; ?>
</body>
</html>
