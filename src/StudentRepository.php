<?php

require_once __DIR__ . '/Student.php';
require_once __DIR__ . '/GraduateStudent.php';
require_once __DIR__ . '/StudentRepositoryInterface.php';

// StudentRepository implements the repository interface with in-memory storage
class StudentRepository implements StudentRepositoryInterface
{
    private array $students = [];

    public function __construct()
    {
        // In-memory array storage
        $this->students = [];
    }

    public function add(Student $student): bool
    {
        if (isset($this->students[$student->getId()])) {
            return false;
        }

        $this->students[$student->getId()] = $student;
        return true;
    }

    public function getAll(): array
    {
        return array_values($this->students);
    }

    public function findById(string $id): ?Student
    {
        $id = trim($id);
        return $this->students[$id] ?? null;
    }

    public function search(string $query): array
    {
        $query = strtolower(trim($query));
        $results = [];

        foreach ($this->students as $student) {
            $matched = str_contains(strtolower($student->getName()), $query) ||
                       str_contains(strtolower($student->getId()), $query) ||
                       str_contains(strtolower($student->getCourse()), $query);

            if ($student instanceof GraduateStudent && str_contains(strtolower($student->getResearchTopic()), $query)) {
                $matched = true;
            }

            if ($matched) {
                $results[] = $student;
            }
        }

        return $results;
    }

    public function update(string $id, ?string $name, ?string $email, ?string $course, ?float $grade, ?string $researchTopic = null): bool
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
        if ($student instanceof GraduateStudent && $researchTopic !== null && trim($researchTopic) !== '') {
            $student->setResearchTopic($researchTopic);
        }

        return true;
    }

    public function delete(string $id): bool
    {
        $id = trim($id);
        if (isset($this->students[$id])) {
            unset($this->students[$id]);
            return true;
        }

        return false;
    }

    public function getStatistics(): array
    {
        $total = count($this->students);
        if ($total === 0) {
            return [
                'total' => 0,
                'averageGrade' => 0,
                'highestGrade' => 0,
                'lowestGrade' => 0,
                'regularCount' => 0,
                'graduateCount' => 0
            ];
        }

        $grades = [];
        $regularCount = 0;
        $graduateCount = 0;

        foreach ($this->students as $student) {
            $grades[] = $student->getGrade();
            if ($student instanceof GraduateStudent) {
                $graduateCount++;
            } else {
                $regularCount++;
            }
        }

        return [
            'total' => $total,
            'averageGrade' => round(array_sum($grades) / $total, 2),
            'highestGrade' => max($grades),
            'lowestGrade' => min($grades),
            'regularCount' => $regularCount,
            'graduateCount' => $graduateCount
        ];
    }
}
