<?php

require_once __DIR__ . '/src/Person.php';
require_once __DIR__ . '/src/Student.php';
require_once __DIR__ . '/src/GraduateStudent.php';
require_once __DIR__ . '/src/StudentRepositoryInterface.php';
require_once __DIR__ . '/src/StudentRepository.php';
require_once __DIR__ . '/src/CliApp.php';

// Initialize repository implementing StudentRepositoryInterface
$repository = new StudentRepository();

// Inject repository into CLI application
$app = new CliApp($repository);

$app->run();
