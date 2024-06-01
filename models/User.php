<?php

declare(strict_types=1);

namespace app\models;

use app\core\Model;

class User extends Model
{
    private string $first_name;
    private string $second_name;
    private int $age;
    private string $email;
    private string $phone;
    private string $password;

    public function __construct(?int $id, string $first_name, string $second_name, int $age, string $email, string $phone, string $password)
    {
        parent::__construct($id);
        $this->first_name = $first_name;
        $this->second_name = $second_name;
        $this->age = $age;
        $this->email = $email;
        $this->phone = $phone;
        $this->password = $password;
    }

    public function getFirstName(): string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): void
    {
        $this->first_name = $first_name;
    }

    public function getSecondName(): string
    {
        return $this->second_name;
    }

    public function setSecondName(string $second_name): void
    {
        $this->second_name = $second_name;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function setAge(int $age): void
    {
        $this->age = $age;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}
