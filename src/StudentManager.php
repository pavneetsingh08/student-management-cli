<?php

require_once __DIR__ . '/Student.php';

// StudentManager class to handle array operations
class StudentManager
{
    private array $students = [];

    public function __construct()
    {
        // Add sample students for testing
        $this->addStudent(new Student('S101', 'Alice Johnson', 'alice@example.com', 'Computer Science', 88.5));
        $this->addStudent(new Student('S102', 'Bob Smith', 'bob@example.com', 'Information Tech', 76.0));
        $this->addStudent(new Student('S103', 'Charlie Brown', 'charlie@example.com', 'Data Science', 92.3));
    }

    // Add a new student
    public function addStudent(Student $student): bool
    {
        if (isset($this->students[$student->getId()])) {
            return false; // ID already exists
        }

        $this->students[$student->getId()] = $student;
        return true;
    }

    // Get all students
    public function getAllStudents(): array
    {
        return array_values($this->students);
    }

    // Find student by ID
    public function findById(string $id): ?Student
    {
        $id = trim($id);
        return $this->students[$id] ?? null;
    }

    // Search students by keyword (ID, name, or course)
    public function search(string $query): array
    {
        $query = strtolower(trim($query));
        $results = [];

        foreach ($this->students as $student) {
            if (
                str_contains(strtolower($student->getName()), $query) ||
                str_contains(strtolower($student->getId()), $query) ||
                str_contains(strtolower($student->getCourse()), $query)
            ) {
                $results[] = $student;
            }
        }

        return $results;
    }

    // Update student details
    public function updateStudent(string $id, ?string $name, ?string $email, ?string $course, ?float $grade): bool
    {
        $student = $this->findById($id);
        if (!$student) {
            return false;
        }

        if ($name !== null && trim($name) !== '') {
            $student->setName($name);
        }
        if ($email !== null && trim($email) !== '') {
            $student->setEmail($email);
        }
        if ($course !== null && trim($course) !== '') {
            $student->setCourse($course);
        }
        if ($grade !== null) {
            $student->setGrade($grade);
        }

        return true;
    }

    // Delete a student by ID
    public function deleteStudent(string $id): bool
    {
        $id = trim($id);
        if (isset($this->students[$id])) {
            unset($this->students[$id]);
            return true;
        }

        return false;
    }

    // Calculate class statistics
    public function getStatistics(): array
    {
        $total = count($this->students);
        if ($total === 0) {
            return [
                'total' => 0,
                'averageGrade' => 0,
                'highestGrade' => 0,
                'lowestGrade' => 0
            ];
        }

        $grades = [];
        foreach ($this->students as $student) {
            $grades[] = $student->getGrade();
        }

        return [
            'total' => $total,
            'averageGrade' => round(array_sum($grades) / $total, 2),
            'highestGrade' => max($grades),
            'lowestGrade' => min($grades)
        ];
    }
}
