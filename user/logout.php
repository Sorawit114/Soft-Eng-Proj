<?php session_start();

unset($_SESSION['id']);
unset($_SESSION['session_id']);
unset($_SESSION['role']);
header("Location:  ../home/aquarium.php");
exit();
?>