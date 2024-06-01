<?php

declare(strict_types=1);

namespace app\models;

use app\core\Model;

class Comment extends Model
{
    private string $comment;
    private int $rate;
    private int $user_id;
    private int $event_id;

    public function __construct(?int $id, string $comment, int $rate, int $user_id, int $event_id)
    {
        parent::__construct($id);
        $this->comment = $comment;
        $this->rate = $rate;
        $this->user_id = $user_id;
        $this->event_id = $event_id;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getRate(): int
    {
        return $this->rate;
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
