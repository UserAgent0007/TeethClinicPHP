<?php
session_start();

// Очистка сесії
$_SESSION = [];
session_destroy();

// Перенаправлення
header("Location: index.php");
exit
?>