<?php
// Redirect to a new page
session_start();
$_SESSION['navigation'] = "1";
header("Location: ./home");
exit(); // Ensure that following code is not executed
?>