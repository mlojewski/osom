<?php

namespace App\Service\Command;

class CreateOrganizationCommand
{
    private $email;
    private $name;
    
    public function __construct(
        string $email,
        string $name
    ) {
        $this->email     = $email;
        $this->name      = $name;
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
    public function getName(): string
    {
        return $this->name;
    }
}
