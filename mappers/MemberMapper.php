<?php

namespace app\mappers;

use app\core\Application;
use app\core\Mapper;
use app\core\Model;

class MemberMapper extends Mapper
{
    private \PDOStatement $insert;
    private \PDOStatement $delete;
    private \PDOStatement $check;

    public function __construct()
    {
        parent::__construct();
        $this->insert = $this->pdo->prepare(
            "INSERT INTO members (user_id, event_id) VALUES (:user_id, :event_id)"
        );

        $this->delete = $this->pdo->prepare(
            "DELETE FROM members WHERE user_id = :user_id AND event_id = :event_id"
        );

        $this->check = $this->pdo->prepare(
            "SELECT COUNT(*) FROM members WHERE user_id = :user_id AND event_id = :event_id"
        );
    }

    public function joinEvent(int $userId, int $eventId): void
    {
        $this->insert->execute([
            ':user_id' => $userId,
            ':event_id' => $eventId
        ]);
    }

    public function leaveEvent(int $userId, int $eventId): void
    {
        $this->delete->execute([
            ':user_id' => $userId,
            ':event_id' => $eventId
        ]);
    }

    public function isUserJoined(int $userId, int $eventId): bool
    {
        $this->check->execute([
            ':user_id' => $userId,
            ':event_id' => $eventId
        ]);
        return $this->check->fetchColumn() > 0;
    }

    public function getJoinedEvents(int $userId): array
    {
        $statement = $this->pdo->prepare(
            "SELECT events.* FROM events 
        JOIN members ON events.id = members.event_id 
        WHERE members.user_id = :user_id"
        );
        $statement->execute([':user_id' => $userId]);
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    protected function doInsert(Model $model): Model { }
    protected function doUpdate(Model $model): void { }
    protected function doDelete(int $id): void { }
    protected function doSelect(int $id): array { }
    protected function doSelectAll(): array { }
    public function getInstance(): Mapper { return $this; }
    public function createObject(array $data): Model { }
}
