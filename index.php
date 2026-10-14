<?php

require_once __DIR__ . '/src/Models/Person.php';
require_once __DIR__ . '/src/Models/Student.php';
require_once __DIR__ . '/src/Models/GraduateStudent.php';
require_once __DIR__ . '/src/Repositories/StudentRepositoryInterface.php';
require_once __DIR__ . '/src/Repositories/StudentRepository.php';
require_once __DIR__ . '/src/Exceptions/StudentNotFoundException.php';
require_once __DIR__ . '/src/Services/StudentService.php';
require_once __DIR__ . '/src/CliApp.php';

// Initialize repository, service, and CLI app
$repository = new StudentRepository();
$service = new StudentService($repository);
$app = new CliApp($service);

$app->run();
