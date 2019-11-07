<?php

namespace App\Service\Command;

class CreateUserCommand
{
    private $email;
    private $password;
    private $role;
    private $name;
    private $lastName;
    
    public function __construct(
        string $email,
        string $password,
        string $role,
        string $name,
        string $lastName
    ) {
        $this->email     = $email;
        $this->password  = $password;
        $this->role      = $role;
        $this->name      = $name;
        $this->lastName  = $lastName;
    }
    
    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
    
    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
    
    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }
    
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }
}
