<?php

require_once __DIR__ . '/Person.php';

// Student class inherits from Person
class Student extends Person
{
    protected string $course;
    protected float $grade;

    public function __construct(string $id, string $name, string $email, string $course, float $grade)
    {
        parent::__construct($id, $name, $email);
        $this->course = trim($course);
        $this->grade = $grade;
    }

    public function getCourse(): string
    {
        return $this->course;
    }

    public function setCourse(string $course): void
    {
        $this->course = trim($course);
    }

    public function getGrade(): float
    {
        return $this->grade;
    }

    public function setGrade(float $grade): void
    {
        $this->grade = $grade;
    }

    public function getType(): string
    {
        return 'Regular';
    }
}
