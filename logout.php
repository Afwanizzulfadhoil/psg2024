<?php
session_start();
session_destroy();
header("Location: ../daashboard/login.php");
?>
