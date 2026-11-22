<?php
session_start(); session_unset(); session_destroy();
session_start(); $_SESSION['notice']="You have been logged out.";
header("Location: index.php"); exit;
?>
