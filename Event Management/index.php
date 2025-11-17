<?php
// Event Management System - Main Page
// Redirect to events or show overview
if(php_sapi_name() === 'cli') {
    exit("This file must be accessed via a web browser.\n");
}

// Uncomment to show setup page on first visit:
// header('Location: setup_test.php');

// Otherwise redirect to events
header('Location: events.php');
exit;

