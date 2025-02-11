<?php
// Start the session to access session variables
session_start();

// Unset all session variables to clear user data
session_unset();

// Destroy the session to log the user out
session_destroy();

// Redirect the user to the index page after logging out
header('Location: index.php');
// Ensure no further code is executed after the redirect
exit;
