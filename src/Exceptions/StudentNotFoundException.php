<?php

// Custom exception thrown when a student is not found
class StudentNotFoundException extends Exception
{
    public function __construct(string $message = "Student not found.")
    {
        parent::__construct($message);
    }
}
