<?php
require_once 'auth.php';

// Initialize Authentication class
// Example output: New Authentication instance created
$auth = new Authentication();

// Process logout
// Example output: ['success' => true, 'message' => 'Logout successful']
$result = $auth->logout();

// Redirect to login page
header('Location: login.php');
exit;