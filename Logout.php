<?php session_start();

unset($_SESSION['id']);
unset($_SESSION['session_id']);
header("Location: aquarium.php");
exit();
?>