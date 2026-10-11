<?php

require_once __DIR__ . '/src/Student.php';
require_once __DIR__ . '/src/StudentManager.php';
require_once __DIR__ . '/src/CliApp.php';

// Create student manager and start CLI app
$manager = new StudentManager();
$app = new CliApp($manager);

$app->run();
