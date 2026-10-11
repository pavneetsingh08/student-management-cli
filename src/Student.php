<?php

// Student class to hold student details
class Student
{
    private string $id;
    private string $name;
    private string $email;
    private string $course;
    private float $grade;

    public function __construct(string $id, string $name, string $email, string $course, float $grade)
    {
        $this->id = trim($id);
        $this->name = trim($name);
        $this->email = trim($email);
        $this->course = trim($course);
        $this->grade = $grade;
    }

    // Getters
    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getCourse(): string
    {
        return $this->course;
    }

    public function getGrade(): float
    {
        return $this->grade;
    }

    // Setters
    public function setName(string $name): void
    {
        $this->name = trim($name);
    }

    public function setEmail(string $email): void
    {
        $this->email = trim($email);
    }

    public function setCourse(string $course): void
    {
        $this->course = trim($course);
    }

    public function setGrade(float $grade): void
    {
        $this->grade = $grade;
    }
}
