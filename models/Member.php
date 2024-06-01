<?php

declare(strict_types=1);

namespace app\models;

use app\core\Model;

class Member extends Model
{
    private int $user_id;
    private int $event_id;

    public function __construct(?int $id, int $user_id, int $event_id)
    {
        parent::__construct($id);
        $this->user_id = $user_id;
        $this->event_id = $event_id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getEventId(): int
    {
        return $this->event_id;
    }
}
