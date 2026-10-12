<?php

require_once __DIR__ . '/Student.php';

// GraduateStudent inherits from Student
class GraduateStudent extends Student
{
    private string $researchTopic;

    public function __construct(string $id, string $name, string $email, string $course, float $grade, string $researchTopic)
    {
        // Call parent Student constructor
        parent::__construct($id, $name, $email, $course, $grade);
        $this->researchTopic = trim($researchTopic);
    }

    public function getResearchTopic(): string
    {
        return $this->researchTopic;
    }

    public function setResearchTopic(string $researchTopic): void
    {
        $this->researchTopic = trim($researchTopic);
    }

    // Override getType method
    public function getType(): string
    {
        return 'Graduate';
    }
}
