<?php

declare(strict_types=1);

namespace app\mappers;

use app\core\Mapper;
use app\core\Model;
use app\models\User;

class UserMapper extends Mapper
{
    private \PDOStatement $select;
    private \PDOStatement $selectAll;
    private \PDOStatement $insert;
    private \PDOStatement $update;
    private \PDOStatement $delete;

    public function __construct()
    {
        parent::__construct();
        $this->select = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $this->selectAll = $this->pdo->prepare("SELECT * FROM users");
        $this->insert = $this->pdo->prepare(
            "INSERT INTO users (first_name, second_name, age, email, phone, password) 
             VALUES (:first_name, :second_name, :age, :email, :phone, :password)"
        );
        $this->delete = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
        $this->update = $this->pdo->prepare(
            "UPDATE users 
             SET first_name=:first_name, second_name=:second_name, age=:age, email=:email, phone=:phone, password=:password 
             WHERE id=:id"
        );
    }

    protected function doInsert(Model $model): Model
    {
        $this->insert->execute([
            ":first_name" => $model->getFirstName(),
            ":second_name" => $model->getSecondName(),
            ":age" => $model->getAge(),
            ":email" => $model->getEmail(),
            ":phone" => $model->getPhone(),
            ":password" => password_hash($model->getPassword(), PASSWORD_DEFAULT)
        ]);
        $model->setId((int)$this->pdo->lastInsertId());
        return $model;
    }

    protected function doUpdate(Model $model): void
    {
        $this->update->execute([
            ":first_name" => $model->getFirstName(),
            ":second_name" => $model->getSecondName(),
            ":age" => $model->getAge(),
            ":email" => $model->getEmail(),
            ":phone" => $model->getPhone(),
            ":password" => password_hash($model->getPassword(), PASSWORD_DEFAULT),
            ":id" => $model->getId()
        ]);
    }

    protected function doDelete(int $id): void
    {
        $this->delete->execute([":id" => $id]);
    }

    protected function doSelect(int $id): array
    {
        $this->select->execute([":id" => $id]);
        return $this->select->fetch(\PDO::FETCH_ASSOC);
    }

    protected function doSelectAll(): array
    {
        $this->selectAll->execute();
        return $this->selectAll->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getInstance(): Mapper
    {
        return $this;
    }

    public function createObject(array $data): Model
    {
        return new User(
            array_key_exists("id", $data) ? $data["id"] : null,
            $data["first_name"],
            $data["second_name"],
            (int)$data["age"],
            $data["email"],
            $data["phone"],
            $data["password"]
        );
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($data) {
            return $this->createObject($data);
        }
        return null;
    }
}
