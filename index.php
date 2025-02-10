<!DOCTYPE html>
<html lang="en">
<head>
    <title>Home</title>
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
    
    <?php
session_start();

if (isset($_SESSION['timeout'])) {
    if (time() > $_SESSION['timeout']) {
        session_unset();
        session_destroy();
        header('Location: login.php');
        exit;
    }
} else {
    $_SESSION['timeout'] = time() + (5 * 60);
}

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) :
    $user_id = $_SESSION['user_id'];

    // Fetch user details
    $details_query = "SELECT d.*, c.class_name, c.section 
                      FROM details d 
                      LEFT JOIN user_class uc ON d.user_id = uc.user_id 
                      LEFT JOIN class c ON uc.class_id = c.class_id 
                      WHERE d.user_id = '$user_id'";
    $details_result = mysqli_query($mysqli, $details_query);
    $details = mysqli_fetch_assoc($details_result);

    // Fetch user subjects
    $subjects_query = "SELECT s.subject_name 
                       FROM subjects s 
                       JOIN user_subjects us ON s.subject_id = us.subject_id 
                       WHERE us.user_id = '$user_id'";
    $subjects_result = mysqli_query($mysqli, $subjects_query);
?>
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
                <td>
                    <?php
                    $subjects = [];
                    while ($subject = mysqli_fetch_assoc($subjects_result)) {
                        $subjects[] = $subject['subject_name'];
                    }
                    echo implode(", ", $subjects);
                    ?>
                </td>
            </tr>
        </table>
    <?php else : ?>
        <p>No details found. Please add your details.</p>
    <?php endif; ?>
<?php endif; ?>

</body>
</html>