# Student Management System (CLI)

A command-line based Student Management application built in PHP using Object-Oriented Programming (OOP) concepts: Inheritance, Interfaces, the Repository Pattern, Service Layer, and Custom Exceptions.

## Project Structure

```text
src/
├── Models/
│   ├── Person.php
│   ├── Student.php
│   └── GraduateStudent.php
├── Services/
│   └── StudentService.php
├── Repositories/
│   ├── StudentRepositoryInterface.php
│   └── StudentRepository.php
├── Exceptions/
│   └── StudentNotFoundException.php
└── CliApp.php
index.php
```

## Architecture

### 1. Inheritance Hierarchy
```text
Person
   ↑ (extends)
Student
   ↑ (extends)
GraduateStudent
```
- `Person`: Base class with common attributes (`id`, `name`, `email`).
- `Student`: Extends `Person` and adds student attributes (`course`, `grade`).
- `GraduateStudent`: Extends `Student` and adds research topic (`researchTopic`).

### 2. Layers & Responsibilities
- **Models**: Defines data entities (`Person`, `Student`, `GraduateStudent`).
- **Repositories**: Manages data persistence and storage (`StudentRepositoryInterface`, `StudentRepository`).
- **Services**: Handles business logic and validation (`StudentService`).
- **Exceptions**: Custom application errors (`StudentNotFoundException`).
- **UI / CLI**: Interactive terminal interface (`CliApp.php`, `index.php`).

## Features
- Add new student records (Regular or Graduate student)
- View all students in a formatted table
- Search students by ID, Name, Course, or Research Topic
- Update student details with validation
- Delete student records with confirmation
- View class statistics (total count, regular count, graduate count, grade averages)
- Custom error handling with `StudentNotFoundException`

## Requirements
- PHP 8.x (or XAMPP)

## How to Run

1. Open a terminal in the project root directory.
2. Run:
   ```bash
   php index.php
   ```
