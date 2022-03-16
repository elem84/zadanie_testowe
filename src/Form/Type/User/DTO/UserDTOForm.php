<?php declare(strict_types=1);

namespace App\Form\Type\User\DTO;

class UserDTOForm
{
    private ?string $username = null;
    private ?string $password = null;

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }
}