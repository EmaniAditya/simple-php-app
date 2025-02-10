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
        <a href="index.php">home</a>
        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) : ?>
            <a href="logout.php">logout</a>
            <a href="details.php">details</a>
            <a href="exams.php">exam dates</a>
            <p>timeout in: <?= ($_SESSION['timeout'] - time()) / 60 ?> minutes</p>
        <?php else : ?>
            <a href="login.php">login</a>
            <a href="signup.php">signup</a>
        <?php endif; ?>
    </nav>

    <?php if (isset($_SESSION['message'])) : ?>
        <p><?= $_SESSION['message'] ?></p>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>


    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) : ?>
        <h1>welcome <?= $_SESSION['username'] ?></h1>
    <?php endif; ?>
</body>

</html>