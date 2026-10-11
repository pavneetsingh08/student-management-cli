# Student Management System (CLI)

A command-line based Student Management application built in PHP using Object-Oriented Programming (OOP) classes.

# Features
- Add new student records
- View all students in a table format
- Search students by ID, Name, or Course
- Update student details
- Delete student records
- View class statistics (total count, average grade, highest, and lowest scores)

# Project Structure
- `index.php`: Main entry point to run the application.
- `src/Student.php`: Student class representing student attributes.
- `src/StudentManager.php`: Class handling in-memory storage and operations.
- `src/CliApp.php`: Class handling the menu interface and user input.

# Requirements
- PHP 8.x installed (or XAMPP)

# How to Run

1. Open a terminal or command prompt in the project root directory.
2. Run the following command:
   ```bash
   php index.php
