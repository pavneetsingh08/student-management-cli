<?php

// Base Person class
class Person
{
    protected string $id;
    protected string $name;
    protected string $email;

    public function __construct(string $id, string $name, string $email)
    {
        $this->id = trim($id);
        $this->name = trim($name);
        $this->email = trim($email);
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

    // Setters
    public function setName(string $name): void
    {
        $this->name = trim($name);
    }

    public function setEmail(string $email): void
    {
        $this->email = trim($email);
    }
}
