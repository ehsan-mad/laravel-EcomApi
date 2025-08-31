<?php

// Laravel Application Entry Point for Render.com
// This file helps Render detect this as a PHP project

// Redirect all requests to the public directory
header('Location: /public/index.php' . ($_SERVER['REQUEST_URI'] ?? ''));
exit;
