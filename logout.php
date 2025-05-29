<?php

require_once 'logger.php';

session_start();
$username = $_SESSION['username'] ?? 'unknown';
logActivity("User '$username' logout");
session_unset();
session_destroy();
header('Location: login.php');
exit;
