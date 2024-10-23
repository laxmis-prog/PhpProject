<?php
// Start the session
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Clear the remember token cookie
setcookie("remember_token", "", time() - 3600, "/");


// Redirect to the login page
header("Location: Kirjaudu.php");
exit;
?>


