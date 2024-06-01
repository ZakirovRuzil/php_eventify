<?php

declare(strict_types=1);

namespace app\mappers;

use app\core\Collection;
use app\core\Mapper;
use app\core\Model;
use app\models\Comment;

class CommentMapper extends Mapper
{
    private \PDOStatement $selectAllByEventId;
    private \PDOStatement $insert;

    public function __construct()
    {
        parent::__construct();
        $this->selectAllByEventId = $this->pdo->prepare("SELECT * FROM feedbacks WHERE event_id = :event_id");
        $this->insert = $this->pdo->prepare(
            "INSERT INTO feedbacks (comment, rate, user_id, event_id) 
             VALUES (:comment, :rate, :user_id, :event_id)"
        );
    }

    public function SelectAllByEventId(int $eventId): Collection
    {
        $this->selectAllByEventId->execute([':event_id' => $eventId]);
        $data = $this->selectAllByEventId->fetchAll(\PDO::FETCH_ASSOC);
        return new Collection($data, $this);
    }

    protected function doInsert(Model $model): Model
    {
        $this->insert->execute([
            ':comment' => $model->getComment(),
            ':rate' => $model->getRate(),
            ':user_id' => $model->getUserId(),
            ':event_id' => $model->getEventId()
        ]);
        $model->setId((int)$this->pdo->lastInsertId());
        return $model;
    }

    protected function doUpdate(Model $model): void
    {
        // Комментарии не обновляются
    }

    protected function doDelete(int $id): void
    {
        // Комментарии не удаляются
    }

    protected function doSelect(int $id): array
    {
        // Не используется
        return [];
    }

    protected function doSelectAll(): array
    {
        // Не используется
        return [];
    }

    public function getInstance(): Mapper
    {
        return $this;
    }

    public function createObject(array $data): Model
    {
        return new Comment(
            array_key_exists("id", $data) ? $data["id"] : null,
            $data["comment"],
            (int)$data["rate"],
            (int)$data["user_id"],
            (int)$data["event_id"]
        );
    }
}
