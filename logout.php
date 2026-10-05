<?php
session_start();
session_unset();
session_destroy();
header("location: index.php"); // Or login page location
exit();
?>