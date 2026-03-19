<?php

class Usuario
{
    public ?int $id;

    public function __construct(
        public string $nome,
        public string $email,
        public string $password,
        public bool $is_admin = false, 
        ?int $id = null
    ) {
        $this->id = $id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }

    public function isAdmin(): bool
    {
        return $this->is_admin;
    }
}