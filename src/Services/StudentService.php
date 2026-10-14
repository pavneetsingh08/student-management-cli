<?php

require_once __DIR__ . '/../Models/Student.php';
require_once __DIR__ . '/../Models/GraduateStudent.php';
require_once __DIR__ . '/../Repositories/StudentRepositoryInterface.php';
require_once __DIR__ . '/../Exceptions/StudentNotFoundException.php';

// StudentService handles business logic and coordinates with the repository
class StudentService
{
    private StudentRepositoryInterface $repository;

    public function __construct(StudentRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function addStudent(Student $student): bool
    {
        // Check if student with same ID already exists
        if ($this->repository->findById($student->getId())) {
            return false;
        }

        return $this->repository->add($student);
    }

    public function getAllStudents(): array
    {
        return $this->repository->getAll();
    }

    public function getStudentById(string $id): Student
    {
        $student = $this->repository->findById($id);
        if (!$student) {
            throw new StudentNotFoundException("Student with ID '$id' was not found.");
        }

        return $student;
    }

    public function searchStudents(string $query): array
    {
        return $this->repository->search($query);
    }

    public function updateStudent(string $id, ?string $name, ?string $email, ?string $course, ?float $grade, ?string $researchTopic = null): bool
    {
        $student = $this->repository->findById($id);
        if (!$student) {
            throw new StudentNotFoundException("Cannot update. Student with ID '$id' was not found.");
        }

        return $this->repository->update($id, $name, $email, $course, $grade, $researchTopic);
    }

    public function deleteStudent(string $id): bool
    {
        $student = $this->repository->findById($id);
        if (!$student) {
            throw new StudentNotFoundException("Cannot delete. Student with ID '$id' was not found.");
        }

        return $this->repository->delete($id);
    }

    public function getStatistics(): array
    {
        return $this->repository->getStatistics();
    }
}
