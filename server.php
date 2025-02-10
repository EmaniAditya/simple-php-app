<?php
session_start();
include('db.php');

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'signup') {
            $username = mysqli_real_escape_string($mysqli, $_POST['username']);
            $password = mysqli_real_escape_string($mysqli, $_POST['password']);

            $check_query = "SELECT id FROM users WHERE username = '$username'";
            $result = mysqli_query($mysqli, $check_query);

            if (mysqli_num_rows($result) > 0) {
                $_SESSION['message'] = "account exists";
                header('Location: signup.php');
                exit;
            }

            $query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";

            if (mysqli_query($mysqli, $query)) {
                $_SESSION['message'] = "signup done, now login";
                header('Location: login.php');
            } else {
                $_SESSION['message'] = "signup failed!";
                header('Location: signup.php');
            }
        } elseif ($_POST['action'] === 'login') {
            $username = mysqli_real_escape_string($mysqli, $_POST['username']);
            $password = mysqli_real_escape_string($mysqli, $_POST['password']);

            $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
            $result = mysqli_query($mysqli, $query);

            if (mysqli_num_rows($result) === 1) {
                $user = mysqli_fetch_assoc($result);
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['timeout'] = time() + (5 * 60);
                header('Location: index.php');
            } else {
                $_SESSION['message'] = "invalid details";
                header('Location: login.php');
            }
        }
    }
}
mysqli_close($mysqli);
