<?php
// logout.php - distrugge la sessione e torna al login
session_start();
session_destroy();
header('location: login.php');
exit();
?>
