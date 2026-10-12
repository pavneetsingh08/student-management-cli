# Student Management System (CLI)

A command-line based Student Management application built in PHP using Object-Oriented Programming (OOP) concepts: Inheritance, Interfaces, and the Repository Pattern.

## Architecture

### 1. Class Inheritance Hierarchy
```text
Person
   ↑ (extends)
Student
   ↑ (extends)
GraduateStudent
```
- **`Person`**: Base class containing shared personal attributes (`id`, `name`, `email`).
- **`Student`**: Extends `Person` and adds student attributes (`course`, `grade`).
- **`GraduateStudent`**: Extends `Student` and adds research attributes (`researchTopic`).

### 2. Repository Pattern
- **`StudentRepositoryInterface`**: Contract defining standard CRUD operations (`add`, `getAll`, `findById`, `search`, `update`, `delete`, `getStatistics`).
- **`StudentRepository`**: Implements `StudentRepositoryInterface` using in-memory array storage.
- **`CliApp`**: Handles the terminal user interface, decoupled via `StudentRepositoryInterface`.

## Features
- Add new student records (Regular or Graduate student)
- View all students in a formatted table
- Search students by ID, Name, Course, or Research Topic
- Update student details (including research topic for graduate students)
- Delete student records with confirmation
- View class statistics (total count, regular count, graduate count, grade averages)

## Requirements
- PHP 8.x (or XAMPP)

## How to Run

1. Open a terminal in the project root directory.
2. Run:
   ```bash
   php index.php
   ```
