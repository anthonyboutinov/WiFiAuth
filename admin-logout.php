<?php
// Begin the session
session_start();

// Unset all of the session variables.
session_unset();

// Destroy the session.
session_destroy();

// Unset "session" cookies

setcookie("acceccLevelAcceptedArray", '', time()-10); // make the cookie expire


// Redirect

header('Location: http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}/admin-login.php"); /* Redirect browser */
exit();
?>