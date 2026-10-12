<?php

require_once __DIR__ . '/Student.php';

// Interface defining repository methods for students
interface StudentRepositoryInterface
{
    public function add(Student $student): bool;
    public function getAll(): array;
    public function findById(string $id): ?Student;
    public function search(string $query): array;
    public function update(string $id, ?string $name, ?string $email, ?string $course, ?float $grade, ?string $researchTopic = null): bool;
    public function delete(string $id): bool;
    public function getStatistics(): array;
}
