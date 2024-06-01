<?php

declare(strict_types=1);

namespace app\mappers;

use app\core\Collection;
use app\core\Mapper;
use app\core\Model;
use app\models\Event;

class EventMapper extends Mapper
{
    private \PDOStatement $select;
    private \PDOStatement $selectAll;
    private \PDOStatement $insert;
    private \PDOStatement $update;
    private \PDOStatement $delete;
    private \PDOStatement $search;

    public function __construct()
    {
        parent::__construct();
        $this->select = $this->pdo->prepare("SELECT * FROM events WHERE id = :id");

        $this->selectAll = $this->pdo->prepare("SELECT * FROM events");

        $this->insert = $this->pdo->prepare(
            "INSERT INTO events (name, short_description, long_description, place, date, time, image, user_id) 
             VALUES (:name, :short_description, :long_description, :place, :date, :time, :image, :user_id)"
        );

        $this->update = $this->pdo->prepare(
            "UPDATE events 
             SET name = :name,
                 short_description = :short_description,
                 long_description = :long_description,
                 place = :place,
                 date = :date,
                 time = :time,
                 image = :image,
                 user_id = :user_id
             WHERE id = :id"
        );

        $this->delete = $this->pdo->prepare("DELETE FROM events WHERE id = :id");

        $this->search = $this->pdo->prepare("SELECT * FROM events WHERE LOWER(name) LIKE :query OR LOWER(short_description) LIKE :query");
    }

    protected function doInsert(Model $model): Model
    {
        $this->insert->execute([
            ':name' => $model->getName(),
            ':short_description' => $model->getShortDescription(),
            ':long_description' => $model->getLongDescription(),
            ':place' => $model->getPlace(),
            ':date' => $model->getDate(),
            ':time' => $model->getTime(),
            ':image' => $model->getImage(),
            ':user_id' => $model->getUserId()
        ]);

        $model->setId((int)$this->pdo->lastInsertId());
        return $model;
    }

    protected function doUpdate(Model $model): void
    {
        $this->update->execute([
            ':name' => $model->getName(),
            ':short_description' => $model->getShortDescription(),
            ':long_description' => $model->getLongDescription(),
            ':place' => $model->getPlace(),
            ':date' => $model->getDate(),
            ':time' => $model->getTime(),
            ':image' => $model->getImage(),
            ':user_id' => $model->getUserId(),
            ':id' => $model->getId()
        ]);
    }

    protected function doDelete(int $id): void
    {
        $this->delete->execute([':id' => $id]);
    }

    protected function doSelect(int $id): array
    {
        $this->select->execute([':id' => $id]);
        $result = $this->select->fetch(\PDO::FETCH_ASSOC);
        return $result !== false ? $result : [];
    }

    protected function doSelectAll(): array
    {
        $this->selectAll->execute();
        return $this->selectAll->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function searchByQuery(string $query): array
    {
        $this->search->execute([':query' => '%' . $query . '%']);
        return $this->search->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getInstance(): Mapper
    {
        return $this;
    }

    public function createObject(array $data): ?Model
    {
        if (empty($data)) {
            return null;
        }

        return new Event(
            array_key_exists("id", $data) ? $data["id"] : null,
            $data["name"],
            $data["short_description"],
            $data["long_description"],
            $data["place"],
            $data["date"],
            $data["time"],
            $data["image"],
            $data["user_id"]
        );
    }

    public function Select(int $id): ?Model
    {
        $data = $this->doSelect($id);
        if (empty($data)) {
            return null;
        }
        return $this->createObject($data);
    }

    public function getEventsByUser(int $userId): array
    {
        $statement = $this->pdo->prepare("SELECT * FROM events WHERE user_id = :user_id");
        $statement->execute([':user_id' => $userId]);
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
}
