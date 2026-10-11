<?php

require_once __DIR__ . '/Student.php';
require_once __DIR__ . '/StudentManager.php';

// CliApp class to handle menu and user input
class CliApp
{
    private StudentManager $manager;

    public function __construct(StudentManager $manager)
    {
        $this->manager = $manager;
    }

    public function run(): void
    {
        while (true) {
            $this->displayMenu();
            $choice = $this->prompt("Enter your choice (1-7): ");

            echo PHP_EOL;

            switch ($choice) {
                case '1':
                    $this->handleAddStudent();
                    break;
                case '2':
                    $this->handleViewAllStudents();
                    break;
                case '3':
                    $this->handleSearchStudent();
                    break;
                case '4':
                    $this->handleUpdateStudent();
                    break;
                case '5':
                    $this->handleDeleteStudent();
                    break;
                case '6':
                    $this->handleViewStatistics();
                    break;
                case '7':
                    echo "=========================================" . PHP_EOL;
                    echo " Exiting program. Goodbye!               " . PHP_EOL;
                    echo "=========================================" . PHP_EOL;
                    return;
                default:
                    echo "Invalid choice. Please choose an option between 1 and 7." . PHP_EOL;
            }

            echo PHP_EOL;
            $this->prompt("Press Enter to continue...");
            echo PHP_EOL;
        }
    }

    private function displayMenu(): void
    {
        echo "=========================================" . PHP_EOL;
        echo "       STUDENT MANAGEMENT SYSTEM         " . PHP_EOL;
        echo "=========================================" . PHP_EOL;
        echo " [1] Add New Student" . PHP_EOL;
        echo " [2] View All Students" . PHP_EOL;
        echo " [3] Search Student" . PHP_EOL;
        echo " [4] Update Student" . PHP_EOL;
        echo " [5] Delete Student" . PHP_EOL;
        echo " [6] View Class Statistics" . PHP_EOL;
        echo " [7] Exit" . PHP_EOL;
        echo "-----------------------------------------" . PHP_EOL;
    }

    private function handleAddStudent(): void
    {
        echo "--- Add Student ---" . PHP_EOL;

        $id = $this->promptRequired("Enter Student ID: ");
        if ($this->manager->findById($id)) {
            echo "Error: Student ID already exists." . PHP_EOL;
            return;
        }

        $name = $this->promptRequired("Enter Name: ");
        $email = $this->promptRequired("Enter Email: ");
        $course = $this->promptRequired("Enter Course: ");
        $grade = $this->promptFloat("Enter Grade (0-100): ", 0.0, 100.0);

        $student = new Student($id, $name, $email, $course, $grade);
        if ($this->manager->addStudent($student)) {
            echo "Student added successfully." . PHP_EOL;
        } else {
            echo "Failed to add student." . PHP_EOL;
        }
    }

    private function handleViewAllStudents(): void
    {
        echo "--- All Students ---" . PHP_EOL;
        $students = $this->manager->getAllStudents();
        $this->renderStudentTable($students);
    }

    private function handleSearchStudent(): void
    {
        echo "--- Search Student ---" . PHP_EOL;
        $query = $this->promptRequired("Enter keyword (ID, Name, or Course): ");
        $results = $this->manager->search($query);

        if (empty($results)) {
            echo "No students found matching '$query'." . PHP_EOL;
            return;
        }

        echo "Found " . count($results) . " student(s):" . PHP_EOL;
        $this->renderStudentTable($results);
    }

    private function handleUpdateStudent(): void
    {
        echo "--- Update Student ---" . PHP_EOL;
        $id = $this->promptRequired("Enter Student ID to update: ");
        $student = $this->manager->findById($id);

        if (!$student) {
            echo "Student not found." . PHP_EOL;
            return;
        }

        echo "Current: " . $student->getName() . " | " . $student->getEmail() . " | " . $student->getCourse() . " | " . $student->getGrade() . PHP_EOL;
        echo "(Press Enter to keep current value)" . PHP_EOL;

        $name = $this->prompt("New Name: ");
        $email = $this->prompt("New Email: ");
        $course = $this->prompt("New Course: ");
        $gradeInput = $this->prompt("New Grade: ");

        $grade = null;
        if (trim($gradeInput) !== '') {
            if (is_numeric($gradeInput)) {
                $grade = (float)$gradeInput;
            } else {
                echo "Invalid grade entered. Keeping previous grade." . PHP_EOL;
            }
        }

        $this->manager->updateStudent(
            $id,
            $name !== '' ? $name : null,
            $email !== '' ? $email : null,
            $course !== '' ? $course : null,
            $grade
        );

        echo "Student updated successfully." . PHP_EOL;
    }

    private function handleDeleteStudent(): void
    {
        echo "--- Delete Student ---" . PHP_EOL;
        $id = $this->promptRequired("Enter Student ID to delete: ");
        $student = $this->manager->findById($id);

        if (!$student) {
            echo "Student not found." . PHP_EOL;
            return;
        }

        $confirm = strtolower($this->prompt("Are you sure you want to delete {$student->getName()}? (y/n): "));
        if ($confirm === 'y' || $confirm === 'yes') {
            $this->manager->deleteStudent($id);
            echo "Student deleted." . PHP_EOL;
        } else {
            echo "Action cancelled." . PHP_EOL;
        }
    }

    private function handleViewStatistics(): void
    {
        echo "--- Statistics ---" . PHP_EOL;
        $stats = $this->manager->getStatistics();

        echo "Total Students : " . $stats['total'] . PHP_EOL;
        echo "Average Grade  : " . $stats['averageGrade'] . PHP_EOL;
        echo "Highest Grade  : " . $stats['highestGrade'] . PHP_EOL;
        echo "Lowest Grade   : " . $stats['lowestGrade'] . PHP_EOL;
    }

    private function renderStudentTable(array $students): void
    {
        if (empty($students)) {
            echo "No student records found." . PHP_EOL;
            return;
        }

        $mask = "| %-8s | %-20s | %-25s | %-18s | %-6s |" . PHP_EOL;
        $line = "+----------+----------------------+---------------------------+--------------------+--------+";

        echo $line . PHP_EOL;
        printf($mask, "ID", "Name", "Email", "Course", "Grade");
        echo $line . PHP_EOL;

        foreach ($students as $student) {
            printf(
                $mask,
                $student->getId(),
                mb_strimwidth($student->getName(), 0, 20, "..."),
                mb_strimwidth($student->getEmail(), 0, 25, "..."),
                mb_strimwidth($student->getCourse(), 0, 18, "..."),
                number_format($student->getGrade(), 1)
            );
        }
        echo $line . PHP_EOL;
    }

    private function prompt(string $message): string
    {
        echo $message;
        $input = fgets(STDIN);
        return $input !== false ? trim($input) : '';
    }

    private function promptRequired(string $message): string
    {
        while (true) {
            $val = $this->prompt($message);
            if ($val !== '') {
                return $val;
            }
            echo "This field cannot be empty. Try again: " . PHP_EOL;
        }
    }

    private function promptFloat(string $message, float $min = 0.0, float $max = 100.0): float
    {
        while (true) {
            $input = $this->prompt($message);
            if (is_numeric($input)) {
                $val = (float)$input;
                if ($val >= $min && $val <= $max) {
                    return $val;
                }
                echo "Please enter a value between $min and $max." . PHP_EOL;
            } else {
                echo "Please enter a valid number." . PHP_EOL;
            }
        }
    }
}
