<?php
// Start the session
session_start();

// Check if the user is logged in (you might want to add more specific checks)
if (isset($_SESSION['user_id'])) {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to the login page or any other desired page
    header("Location: user_login.php"); // Replace with your login page URL
    exit();
} else {
    // If the user is not logged in, you can redirect them to the login page
    header("Location: user_dashboard.php"); // Replace with your login page URL
    exit();
}
?>