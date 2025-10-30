<?php

class Admin {

    private int $id;
    private string $email;
    private string $token;

    public function __construct(int $id, string $email, string $token) {
        $this->id = $id;
        $this->email = $email;
        $this->token = $token;
    }

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function getToken(): string {
        return $this->token;
    }

    public function setToken(string $token): void {
        $this->token = $token;
    }
    
}
