<?php
session_start();

// Clear the order variables, then wipe and destroy the session.
unset($_SESSION['dessert'], $_SESSION['drink'], $_SESSION['drinkSize']);
$_SESSION = [];
session_destroy();

// Back to a clean dining room (no leftover selections).
header('Location: index.php');
exit;
