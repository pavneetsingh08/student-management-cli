<?php

require_once __DIR__ . '/Models/Student.php';
require_once __DIR__ . '/Models/GraduateStudent.php';
require_once __DIR__ . '/Services/StudentService.php';
require_once __DIR__ . '/Exceptions/StudentNotFoundException.php';

// CliApp class to handle menu and user interactions
class CliApp
{
    private StudentService $service;

    public function __construct(StudentService $service)
    {
        $this->service = $service;
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
        echo "Select Student Type:" . PHP_EOL;
        echo " [1] Regular Student" . PHP_EOL;
        echo " [2] Graduate Student" . PHP_EOL;
        $typeChoice = $this->prompt("Choose (1 or 2): ");

        $id = $this->promptRequired("Enter Student ID: ");
        try {
            $this->service->getStudentById($id);
            echo "Error: Student ID already exists." . PHP_EOL;
            return;
        } catch (StudentNotFoundException $e) {
            // ID is free to use
        }

        $name = $this->promptRequired("Enter Name: ");
        $email = $this->promptRequired("Enter Email: ");
        $course = $this->promptRequired("Enter Course: ");
        $grade = $this->promptFloat("Enter Grade (0-100): ", 0.0, 100.0);

        if ($typeChoice === '2') {
            $researchTopic = $this->promptRequired("Enter Research Topic: ");
            $student = new GraduateStudent($id, $name, $email, $course, $grade, $researchTopic);
        } else {
            $student = new Student($id, $name, $email, $course, $grade);
        }

        if ($this->service->addStudent($student)) {
            echo "Student (" . $student->getType() . ") added successfully." . PHP_EOL;
        } else {
            echo "Failed to add student." . PHP_EOL;
        }
    }

    private function handleViewAllStudents(): void
    {
        echo "--- All Students ---" . PHP_EOL;
        $students = $this->service->getAllStudents();
        $this->renderStudentTable($students);
    }

    private function handleSearchStudent(): void
    {
        echo "--- Search Student ---" . PHP_EOL;
        $query = $this->promptRequired("Enter keyword (ID, Name, Course, or Topic): ");
        $results = $this->service->searchStudents($query);

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

        try {
            $student = $this->service->getStudentById($id);
        } catch (StudentNotFoundException $e) {
            echo $e->getMessage() . PHP_EOL;
            return;
        }

        echo "Type: " . $student->getType() . PHP_EOL;
        echo "Current: " . $student->getName() . " | " . $student->getEmail() . " | " . $student->getCourse() . " | " . $student->getGrade();
        if ($student instanceof GraduateStudent) {
            echo " | Topic: " . $student->getResearchTopic();
        }
        echo PHP_EOL;
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

        $researchTopic = null;
        if ($student instanceof GraduateStudent) {
            $topicInput = $this->prompt("New Research Topic: ");
            if (trim($topicInput) !== '') {
                $researchTopic = $topicInput;
            }
        }

        try {
            $this->service->updateStudent(
                $id,
                $name !== '' ? $name : null,
                $email !== '' ? $email : null,
                $course !== '' ? $course : null,
                $grade,
                $researchTopic
            );
            echo "Student updated successfully." . PHP_EOL;
        } catch (StudentNotFoundException $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }

    private function handleDeleteStudent(): void
    {
        echo "--- Delete Student ---" . PHP_EOL;
        $id = $this->promptRequired("Enter Student ID to delete: ");

        try {
            $student = $this->service->getStudentById($id);
        } catch (StudentNotFoundException $e) {
            echo $e->getMessage() . PHP_EOL;
            return;
        }

        $confirm = strtolower($this->prompt("Are you sure you want to delete {$student->getName()}? (y/n): "));
        if ($confirm === 'y' || $confirm === 'yes') {
            try {
                $this->service->deleteStudent($id);
                echo "Student deleted." . PHP_EOL;
            } catch (StudentNotFoundException $e) {
                echo $e->getMessage() . PHP_EOL;
            }
        } else {
            echo "Action cancelled." . PHP_EOL;
        }
    }

    private function handleViewStatistics(): void
    {
        echo "--- Statistics ---" . PHP_EOL;
        $stats = $this->service->getStatistics();

        echo "Total Students    : " . $stats['total'] . PHP_EOL;
        echo "Regular Students  : " . $stats['regularCount'] . PHP_EOL;
        echo "Graduate Students : " . $stats['graduateCount'] . PHP_EOL;
        echo "Average Grade     : " . $stats['averageGrade'] . PHP_EOL;
        echo "Highest Grade     : " . $stats['highestGrade'] . PHP_EOL;
        echo "Lowest Grade      : " . $stats['lowestGrade'] . PHP_EOL;
    }

    private function renderStudentTable(array $students): void
    {
        if (empty($students)) {
            echo "No student records found." . PHP_EOL;
            return;
        }

        $mask = "| %-6s | %-10s | %-20s | %-24s | %-18s | %-6s | %-25s |" . PHP_EOL;
        $line = "+--------+------------+----------------------+--------------------------+--------------------+--------+---------------------------+";

        echo $line . PHP_EOL;
        printf($mask, "ID", "Type", "Name", "Email", "Course", "Grade", "Research Topic");
        echo $line . PHP_EOL;

        foreach ($students as $student) {
            $topic = ($student instanceof GraduateStudent) ? $student->getResearchTopic() : "-";

            printf(
                $mask,
                $student->getId(),
                $student->getType(),
                $student->getName(),
                $student->getEmail(),
                $student->getCourse(),
                $student->getGrade(),
                $topic
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
