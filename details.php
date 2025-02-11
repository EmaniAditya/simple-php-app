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

$class_query = "SELECT class_id, class_name, section FROM class";
$class_result = mysqli_query($mysqli, $class_query);
$classes = [];
while ($row = mysqli_fetch_assoc($class_result)) {
    $classes[] = $row;
}

$user_class_query = "SELECT class_id FROM user_class WHERE user_id = '$user_id'";
$user_class_result = mysqli_query($mysqli, $user_class_query);
$user_class = mysqli_fetch_assoc($user_class_result)['class_id'] ?? null;

$all_subjects_query = "SELECT subject_id, subject_name FROM subjects";
$all_subjects_result = mysqli_query($mysqli, $all_subjects_query);
$all_subjects = [];
while ($row = mysqli_fetch_assoc($all_subjects_result)) {
    $all_subjects[] = $row;
}

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
    <title>details</title>
</head>
<body>
    <nav>
        <a href="index.php">home</a>
        <a href="details.php">details</a>
        <a href="subjects.php">subjects</a>
        <a href="logout.php">logout</a>
    </nav>

    <p>timeout in: <?= ($_SESSION['timeout'] - time()) / 60 ?> minutes</p>

    <h1>update your details</h1>
    <form method="POST" action="server2.php">
        <label for="name">name:</label>
        <input type="text" id="name" name="name" value="<?= $details['name'] ?? '' ?>" required><br><br>

        <label for="roll_no">roll no:</label>
        <input type="text" id="roll_no" name="roll_no" value="<?= $details['roll_no'] ?? '' ?>" required><br><br>

        <label>class:</label>
        <?php foreach ($classes as $class) : ?>
            <input type="radio" name="class_id" value="<?= $class['class_id'] ?>"
                <?= $class['class_id'] == $user_class ? 'checked' : '' ?>>
            <?= $class['class_name'] . ' - ' . $class['section'] ?><br>
        <?php endforeach; ?>
        <br>

        <label>subjects:</label><br>
        <?php foreach ($all_subjects as $subject) : ?>
            <input type="checkbox" name="subjects[]" value="<?= $subject['subject_id'] ?>"
                <?= in_array($subject['subject_id'], $user_subjects) ? 'checked' : '' ?>>
            <?= $subject['subject_name'] ?><br>
        <?php endforeach; ?>
        <br>

        <button type="submit" name="action" value="update">update</button>
        <button type="submit" name="action" value="delete">delete</button>
    </form>
</body>
</html>
