<?php
/**
 * Admin Logout
 */
session_start();
unset($_SESSION['admin_logged_in']);
unset($_SESSION['admin_user']);
session_destroy();
header('Location: login.php');
exit;
